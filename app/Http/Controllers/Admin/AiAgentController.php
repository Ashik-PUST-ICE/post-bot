<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiAgentSetting;
use App\Models\KeywordRule;
use App\Models\PlatformConnection;
use App\Services\Ai\AiServiceFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AiAgentController extends Controller
{
    /**
     * Show AI Agent settings page.
     */
    public function index()
    {
        $data['title']        = __('AI Configuration');
        $data['activeAiAgent'] = 'active';
        $data['settings']     = AiAgentSetting::forUser(auth()->id());

        // Pass all provider/model data for the view
        $data['allProviders']      = aiProviders();
        $data['providerColors']    = aiProviderColors();
        $data['providerIcons']     = aiProviderIcons();
        $data['providerApiDocs']   = aiProviderApiDocs();
        $data['modelsForProvider'] = [];
        foreach (array_keys(aiProviders()) as $p) {
            $data['modelsForProvider'][$p] = aiModelsForProvider($p);
        }

        return view('admin.ai-agent.index', $data);
    }

    /**
     * Show Agent Knowledge page.
     */
    public function knowledge()
    {
        $data['title']            = __('Agent Knowledge');
        $data['activeAiKnowledge'] = 'active';
        $data['settings']         = AiAgentSetting::forUser(auth()->id());
        $data['keywordRules']     = KeywordRule::where('user_id', auth()->id())
            ->with('platformConnection')
            ->orderByDesc('priority')
            ->orderByDesc('id')
            ->get();
        $data['platforms'] = PlatformConnection::where('user_id', auth()->id())
            ->where('status', STATUS_ACTIVE)->get();

        return view('admin.ai-agent.knowledge', $data);
    }

    /**
     * Update AI agent settings + API keys.
     */
    public function update(Request $request)
    {
        $request->validate([
            'ai_provider'          => 'required|string|in:claude,openai,gemini,grok,deepseek',
            'ai_model'             => 'required|string|max:100',
            'system_prompt'        => 'nullable|string|max:4000',
            'business_context'     => 'nullable|string|max:8000',
            'language_mode'        => 'required|string|max:10',
            'reply_delay_seconds'  => 'required|integer|min:0|max:60',
            'confidence_threshold' => 'required|integer|min:0|max:100',
            'max_tokens'           => 'required|integer|min:64|max:4096',
        ]);

        try {
            DB::beginTransaction();

            $settings = AiAgentSetting::forUser(auth()->id());

            $updateData = [
                'ai_provider'          => $request->ai_provider,
                'ai_model'             => $request->ai_model,
                'system_prompt'        => $request->system_prompt,
                'business_context'     => $request->business_context,
                'language_mode'        => $request->language_mode,
                'sentiment_analysis'   => $request->boolean('sentiment_analysis') ? STATUS_ACTIVE : DEACTIVATE,
                'smart_suggestions'    => $request->boolean('smart_suggestions')   ? STATUS_ACTIVE : DEACTIVATE,
                'spam_detection'       => $request->boolean('spam_detection')      ? STATUS_ACTIVE : DEACTIVATE,
                'conversation_memory'  => $request->boolean('conversation_memory') ? STATUS_ACTIVE : DEACTIVATE,
                'auto_reply_enabled'   => $request->boolean('auto_reply_enabled')  ? STATUS_ACTIVE : DEACTIVATE,
                'reply_delay_seconds'  => $request->reply_delay_seconds,
                'confidence_threshold' => $request->confidence_threshold,
                'max_tokens'           => $request->max_tokens,
            ];

            // Only overwrite API keys if explicitly provided (non-empty)
            foreach (['claude_api_key', 'openai_api_key', 'gemini_api_key', 'grok_api_key', 'deepseek_api_key'] as $keyField) {
                if ($request->filled($keyField)) {
                    $updateData[$keyField] = $request->input($keyField);
                }
            }

            $settings->update($updateData);

            DB::commit();
            return response()->json(['status' => true, 'message' => __(UPDATED_SUCCESSFULLY)]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => getErrorMessage($e, $e->getMessage())]);
        }
    }

    /**
     * Test connectivity of a given provider + API key.
     * Called via AJAX from the settings page "Test Connection" button.
     */
    public function testConnection(Request $request)
    {
        $request->validate([
            'provider' => 'required|string|in:claude,openai,gemini,grok,deepseek',
            'api_key'  => 'required|string|min:10',
            'model'    => 'nullable|string|max:100',
        ]);

        try {
            $service = AiServiceFactory::makeForProvider(
                $request->provider,
                $request->api_key,
                $request->model ?? ''
            );
            $result = $service->testConnection();
            return response()->json([
                'status'  => $result['ok'],
                'message' => $result['message'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Return model list for a provider (for dynamic select via AJAX).
     */
    public function modelsForProvider(Request $request)
    {
        $provider = $request->query('provider', '');
        $models   = aiModelsForProvider($provider);
        return response()->json(['status' => true, 'models' => $models]);
    }

    /**
     * Store a keyword rule.
     */
    public function storeKeyword(Request $request)
    {
        $action = $request->input('action', 'reply');

        $request->validate([
            'keyword'        => 'required|string|max:255',
            'match_type'     => 'required|integer|in:1,2,3',
            'action'         => 'required|string|in:reply,escalate,ignore',
            'reply_template' => $action === 'reply' ? 'required|string|max:2000' : 'nullable|string|max:2000',
        ]);

        try {
            DB::beginTransaction();

            KeywordRule::create([
                'user_id'                => auth()->id(),
                'tenant_id'              => auth()->user()->tenant_id,
                'platform_connection_id' => $request->platform_connection_id ?: null,
                'keyword'                => $request->keyword,
                'match_type'             => $request->match_type,
                'action'                 => $action,
                'reply_template'         => $request->reply_template ?? '',
                'use_ai'                 => $request->boolean('use_ai') ? STATUS_ACTIVE : DEACTIVATE,
                'status'                 => STATUS_ACTIVE,
                'priority'               => $request->input('priority', 0),
            ]);

            DB::commit();
            return response()->json(['status' => true, 'message' => __(CREATED_SUCCESSFULLY)]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => getErrorMessage($e, $e->getMessage())]);
        }
    }

    /**
     * Delete a keyword rule.
     */
    public function destroyKeyword($id)
    {
        try {
            KeywordRule::where('user_id', auth()->id())->findOrFail($id)->delete();
            return response()->json(['status' => true, 'message' => __(DELETED_SUCCESSFULLY)]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
