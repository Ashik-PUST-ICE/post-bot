<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LanguageRequest;
use App\Http\Services\LanguageService;
use App\Models\FileManager;
use App\Models\Language;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LanguageController extends Controller
{
    use ResponseTrait;

    private $languageService;

    public function __construct()
    {
        $this->languageService = new LanguageService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->languageService->getAllData();
        }
        $data['title'] = __('Manage Language');
        $data['showManageApplicationSetting'] = 'show';
        $data['activeLanguagesSetting'] = 'active';
        return view('admin.setting.languages.index', $data);
    }

    public function store(LanguageRequest $request)
    {
        return $this->languageService->store($request);
    }

    public function edit($id)
    {
        $data['language'] = Language::findOrFail($id);
        return view('admin.setting.languages.edit-form', $data);
    }

    public function update(Request $request, $id)
    {
        return $this->languageService->update($request, $id);
    }

    public function translateLanguage($id)
    {
        $data['title'] = __('Translate');
        $data['showManageApplicationSetting'] = 'show';
        $data['activeLanguagesSetting'] = 'active-color-one';
        $language = Language::findOrFail($id);
        $iso_code = $language->iso_code;

        $path = resource_path("lang/$iso_code.json");
        if (!file_exists($path)) file_put_contents($path, '{}');

        $translators = collect(json_decode(file_get_contents($path), true));

        $search = request()->get('search', '');
        if ($search) {
            $translators = $translators->filter(function ($value, $key) use ($search) {
                return str_contains(strtolower($key), strtolower($search))
                    || str_contains(strtolower($value), strtolower($search));
            });
        }

        $perPage = 50;
        $page = request()->get('page', 1);
        $total = $translators->count();

        $translators = $translators->slice(($page - 1) * $perPage, $perPage);

        if (request()->ajax()) {
            return view('admin.setting.languages.partials.translations_table', compact(
                'translators', 'language', 'total', 'perPage', 'page', 'search'
            ))->render();
        }

        $title = __('Translate');
        $languages = Language::where('iso_code', '!=', $iso_code)->get();

        return view('admin.setting.languages.translate', array_merge($data, compact(
            'translators', 'language', 'total', 'perPage', 'page', 'search', 'languages', 'title'
        )))->with('currentPage', $page);
    }

    public function updateLanguage(Request $request, $id)
    {
        $request->validate([
            'key' => 'required',
            'val' => 'required'
        ]);
        return $this->languageService->updateLang($request, $id);
    }

    public function delete($id)
    {
        $lang = Language::findOrFail($id);
        if ($lang->default == STATUS_ACTIVE) {
            $message = __('You Cannot delete default language');
            return $this->error([], $message);
        } else if(Language::count() == 1){
            $message = __('You need minimum one language to running this application');
            return $this->error([], $message);
        }

        $lang->delete();
        return redirect()->back()->with('success', __('Deleted Successfully'));
    }

    public function import(Request $request)
    {
        $language = Language::where('iso_code', $request->import)->firstOrFail();
        $currentLang = Language::where('iso_code', $request->current)->firstOrFail();
        $contents = file_get_contents(resource_path() . "/lang/$language->iso_code.json");
        file_put_contents(resource_path() . "/lang/$currentLang->iso_code.json", $contents);
        $message = UPDATED_SUCCESSFULLY;
        return $this->success([], $message);
    }

    public function updateTranslate(Request $request, $id)
    {
        $request->validate([
            'key' => 'required',
            'val' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $language = Language::findOrFail($id);
            $key = $request->key;
            $val = $request->val;
            $is_new = $request->is_new;
            $path = resource_path() . "/lang/$language->iso_code.json";
            $file_data = json_decode(file_get_contents($path), 1);

            if (!array_key_exists($key, $file_data)) {
                $file_data = array($key => $val) + $file_data;
            } else if ($is_new) {
                $message = __("Already Exist");
                return $this->error([], $message);
            } else {
                $file_data[$key] = $val;
            }
            unlink($path);
            file_put_contents($path, json_encode($file_data));
            DB::commit();
            $message = UPDATED_SUCCESSFULLY;
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }


    public function download($id)
    {
        try {
            $language = Language::findOrFail($id);
            $filePath = resource_path("lang/{$language->iso_code}.json");

            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'Language file does not exist.');
            }

            $fileName = $language->iso_code . '.json';

            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/json',
            ]);
        } catch (\Exception $e) {
            $message = getErrorMessage($e, $e->getMessage());
            return redirect()->back()->with('error', $message);
        }
    }

    public function upload(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:json|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $language = Language::findOrFail($id);
            $uploadedFile = $request->file('file');
            $jsonContent = file_get_contents($uploadedFile->getRealPath());

            // Validate JSON
            $decoded = json_decode($jsonContent, true);
            if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                return $this->error([], 'Invalid JSON file.');
            }

            $path = resource_path("lang/{$language->iso_code}.json");

            // Merge or replace
            if ($request->has('replace') && $request->replace) {
                // Replace existing JSON completely
                file_put_contents($path, json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            } else {
                // Merge with existing JSON
                $existing = file_exists($path) ? json_decode(file_get_contents($path), true) : [];
                $merged = array_merge($existing, $decoded);
                file_put_contents($path, json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }

            DB::commit();
            return $this->success([], __('File uploaded successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }
}
