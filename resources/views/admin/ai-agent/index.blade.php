@extends('admin.layouts.app')
@push('title'){{ $title }}@endpush

@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">

    {{-- Hidden route inputs (read by ai-agent.js) --}}
    <input type="hidden" id="testConnectionRoute"   value="{{ route('admin.ai-agent.test.connection') }}">
    <input type="hidden" id="modelsForProviderRoute" value="{{ route('admin.ai-agent.models.for.provider') }}">
    <input type="hidden" id="providerColorsJson"
        value="{{ json_encode($providerColors) }}">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap g-10 pb-26">
        <div>
            <h4 class="fs-24 fw-600 lh-29 text-textBlack">{{ __('AI Configuration') }}</h4>
            <p class="fs-14 fw-400 text-para-text mt-5">{{ __('Technical setup: API providers, models and system-wide limits.') }}</p>
        </div>
    </div>

    <form class="ajax" action="{{ route('admin.ai-agent.update') }}" method="POST" data-handler="commonResponse">
    @csrf
    {{-- Preserve knowledge fields --}}
    <input type="hidden" name="system_prompt" value="{{ $settings->system_prompt }}">
    <input type="hidden" name="business_context" value="{{ $settings->business_context }}">

    <div class="row rg-20">

        {{-- MAIN COLUMN --}}
        <div class="col-xl-8">

            {{-- Tabs Header --}}
            <div class="bd-one bd-c-stroke bd-ra-10 bg-white mb-4">
                <style>
                    #agentConfigTabs {
                        background: #f9fafb;
                        padding: 10px 15px !important;
                        border-bottom: 1px solid #e5e7eb;
                        gap: 8px !important;
                        border-radius: 10px 10px 0 0;
                    }
                    .agent-tab-link {
                        border-radius: 8px !important;
                        transition: all 0.2s ease;
                        border: 1px solid transparent !important;
                        color: #4b5563 !important;
                        white-space: nowrap;
                    }
                    .agent-tab-link:hover {
                        background: #f3f4f6;
                        color: #111827 !important;
                    }
                    .agent-tab-link:focus {
                        outline: none;
                        box-shadow: none !important;
                    }
                    .agent-tab-link.active {
                        background: white !important;
                        border-color: #e5e7eb !important;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                        color: var(--main-color, #6366f1) !important;
                        font-weight: 600 !important;
                    }
                </style>
                <ul class="nav d-flex flex-nowrap overflow-auto" id="agentConfigTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link agent-tab-link active d-flex align-items-center cg-7 py-12 px-18"
                            id="tab-provider-btn" data-bs-toggle="tab" data-bs-target="#tab-provider" type="button" role="tab">
                            <i class="fa-solid fa-server fs-14"></i> {{ __('Provider & API') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link agent-tab-link d-flex align-items-center cg-7 py-12 px-18"
                            id="tab-tuning-btn" data-bs-toggle="tab" data-bs-target="#tab-tuning" type="button" role="tab">
                            <i class="fa-solid fa-sliders fs-14"></i> {{ __('Feature Toggles') }}
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-25" id="agentConfigTabContent">
                    {{-- ── TAB 1: Provider & API ────────────────────────────────────── --}}
                    <div class="tab-pane fade show active" id="tab-provider" role="tabpanel">
                        <div class="row">
                            <div class="col-xl-12">
                                {{-- Provider Cards --}}
                                <div class="mb-25">
                                    <h5 class="fs-15 fw-600 text-textBlack mb-15">
                                        {{ __('Select Active Provider') }}
                                    </h5>
                                    <div class="row rg-12" id="providerCards">
                                        @foreach($allProviders as $providerKey => $providerLabel)
                                        @php
                                            $isActive = $settings->ai_provider === $providerKey;
                                            $color    = $providerColors[$providerKey];
                                            $keyField = $providerKey . '_api_key';
                                            $hasKey   = !empty($settings->getRawOriginal($keyField));
                                        @endphp
                                        <div class="col-md-4 col-6">
                                            <label class="provider-card d-flex align-items-center cg-12 bd-one bd-ra-10 p-12 cursor-pointer w-100"
                                                style="border-color:{{ $isActive ? $color : '#e5e7eb' }};
                                                       background:{{ $isActive ? $color.'12' : '#fff' }};
                                                       transition:all .2s;">
                                                <input type="radio" name="ai_provider" value="{{ $providerKey }}"
                                                    class="d-none provider-radio" {{ $isActive ? 'checked' : '' }}>
                                                <div class="wh-32 bd-ra-8 d-flex align-items-center justify-content-center flex-shrink-0"
                                                    style="background:{{ $color }}1a;">
                                                    <i class="{{ $providerIcons[$providerKey] }} fs-16" style="color:{{ $color }}"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="fs-13 fw-600 text-textBlack text-truncate">{{ $providerLabel }}</p>
                                                    @if($hasKey)
                                                        <span class="fs-11 fw-600" style="color:#10b981;">
                                                            <i class="fa-solid fa-circle-check me-3 fs-10"></i>{{ __('Configured') }}
                                                        </span>
                                                    @else
                                                        <span class="fs-11 text-para-text">{{ __('No key') }}</span>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Per-Provider API Key Panels --}}
                                @foreach($allProviders as $providerKey => $providerLabel)
                                @php
                                    $color    = $providerColors[$providerKey];
                                    $keyField = $providerKey . '_api_key';
                                    $hasKey   = !empty($settings->getRawOriginal($keyField));
                                @endphp
                                <div class="provider-key-panel bd-one bd-c-stroke bd-ra-10 p-20 mb-20 {{ $settings->ai_provider === $providerKey ? '' : 'd-none' }}"
                                     style="background:#f8fafc;" id="panel-{{ $providerKey }}">
                                    <div class="d-flex align-items-center justify-content-between pb-12 bd-b-one bd-c-stroke mb-15">
                                        <div class="d-flex align-items-center cg-10">
                                            <div class="wh-30 bd-ra-8 d-flex align-items-center justify-content-center" style="background:{{ $color }}1a;">
                                                <i class="{{ $providerIcons[$providerKey] }} fs-14" style="color:{{ $color }}"></i>
                                            </div>
                                            <h5 class="fs-14 fw-600 text-textBlack">{{ $providerLabel }} — {{ __('API Key & Model') }}</h5>
                                        </div>
                                        <a href="{{ $providerApiDocs[$providerKey] }}" target="_blank"
                                            class="fs-11 text-main-color d-flex align-items-center cg-5">
                                            {{ __('Get API Key') }} <i class="fa-solid fa-arrow-up-right-from-square fs-10 ms-4"></i>
                                        </a>
                                    </div>
                                    <div class="row rg-15">
                                        <div class="col-md-12">
                                            <label class="zForm-label fs-13">{{ __('API Key') }}</label>
                                            <div class="d-flex align-items-center cg-10">
                                                <div class="position-relative flex-grow-1">
                                                    <input type="password" name="{{ $keyField }}"
                                                        id="key-{{ $providerKey }}" class="form-control zForm-control"
                                                        placeholder="{{ $hasKey ? '••••••••••••••••••••' : __('Paste your API key here') }}">
                                                    <button type="button" class="border-0 bg-transparent position-absolute top-50 translate-middle-y toggle-key-vis"
                                                        data-target="key-{{ $providerKey }}" style="right:12px;">
                                                        <i class="fa-solid fa-eye fs-13 text-para-text"></i>
                                                    </button>
                                                </div>
                                                <button type="button"
                                                    class="py-11 px-16 bd-one bd-ra-4 bd-c-stroke bg-white fs-13 fw-500 text-textBlack flex-shrink-0 test-connection-btn"
                                                    data-provider="{{ $providerKey }}">
                                                    <i class="fa-solid fa-plug-circle-check me-5"></i>{{ __('Test') }}
                                                </button>
                                            </div>
                                            <p class="fs-12 mt-6 test-result-{{ $providerKey }}"></p>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="zForm-label fs-13">{{ __('Select Model') }}</label>
                                            <select name="ai_model" id="model-{{ $providerKey }}" class="form-control zForm-control model-select">
                                                @foreach($modelsForProvider[$providerKey] as $modelSlug => $modelLabel)
                                                    <option value="{{ $modelSlug }}"
                                                        {{ $settings->ai_model === $modelSlug ? 'selected' : '' }}>
                                                        {{ $modelLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- ── TAB 2: Advanced Tuning ──────────────────────────────────── --}}
                    <div class="tab-pane fade" id="tab-tuning" role="tabpanel">
                        <div class="row rg-20">
                            <div class="col-md-12">
                                <div class="d-flex flex-column rg-10">
                                    @php
                                        $toggles = [
                                            ['name' => 'auto_reply_enabled',  'label' => __('Auto Reply'),          'desc' => __('AI replies automatically to incoming messages.'),          'val' => $settings->auto_reply_enabled],
                                            ['name' => 'sentiment_analysis',  'label' => __('Sentiment Analysis'),  'desc' => __('Detect customer mood and adjust tone accordingly.'),        'val' => $settings->sentiment_analysis],
                                            ['name' => 'smart_suggestions',   'label' => __('Smart Suggestions'),   'desc' => __('Suggest replies for human approval before sending.'),       'val' => $settings->smart_suggestions],
                                            ['name' => 'spam_detection',      'label' => __('Spam Detection'),      'desc' => __('Automatically ignore spam messages.'),                     'val' => $settings->spam_detection],
                                            ['name' => 'conversation_memory', 'label' => __('Conversation Memory'), 'desc' => __('Include past messages in context for continuity.'),  'val' => $settings->conversation_memory],
                                        ];
                                    @endphp
                                    @foreach($toggles as $t)
                                    <div class="d-flex align-items-center justify-content-between bd-one bd-c-stroke bd-ra-8 p-14 bg-body">
                                        <div>
                                            <p class="fs-14 fw-600 text-textBlack">{{ $t['label'] }}</p>
                                            <p class="fs-12 text-para-text mt-2">{{ $t['desc'] }}</p>
                                        </div>
                                        <div class="zCheck form-check form-switch flex-shrink-0 ms-15">
                                            <input class="form-check-input" type="checkbox" name="{{ $t['name'] }}"
                                                value="1" role="switch" {{ $t['val'] == STATUS_ACTIVE ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="zForm-label">{{ __('Reply Language') }}</label>
                                <select name="language_mode" class="form-control zForm-control">
                                    @foreach(aiLanguageModes() as $val => $label)
                                        <option value="{{ $val }}" {{ $settings->language_mode == $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="zForm-label">{{ __('Confidence Threshold (%)') }}</label>
                                <input type="number" name="confidence_threshold" min="0" max="100"
                                    class="form-control zForm-control" value="{{ $settings->confidence_threshold }}">
                            </div>
                            <div class="col-md-6">
                                <label class="zForm-label">{{ __('Reply Delay (s)') }}</label>
                                <input type="number" name="reply_delay_seconds" min="0" max="60"
                                    class="form-control zForm-control" value="{{ $settings->reply_delay_seconds }}">
                            </div>
                            <div class="col-md-6">
                                <label class="zForm-label">{{ __('Max Response Tokens') }}</label>
                                <input type="number" name="max_tokens" min="64" max="4096"
                                    class="form-control zForm-control" value="{{ $settings->max_tokens ?? 512 }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-25 bd-t-one bd-c-stroke d-flex justify-content-end">
                    <button type="submit" class="py-13 px-25 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">
                        <i class="fa-solid fa-save me-6"></i> {{ __('Save Configuration') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Help Column --}}
        <div class="col-xl-4">
            <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25">
                <h5 class="fs-16 fw-600 text-textBlack pb-18 bd-b-one bd-c-stroke mb-20">{{ __('Configuration Guide') }}</h5>
                <ul class="d-flex flex-column rg-15">
                    <li class="d-flex align-items-start cg-10">
                        <i class="fa-solid fa-circle-info mt-4 text-main-color fs-12"></i>
                        <p class="fs-13 text-para-text">
                            <strong>{{ __('API Key:') }}</strong> {{ __('Ensure your API key has enough quota and access to the selected model.') }}
                        </p>
                    </li>
                    <li class="d-flex align-items-start cg-10">
                        <i class="fa-solid fa-circle-info mt-4 text-main-color fs-12"></i>
                        <p class="fs-13 text-para-text">
                            <strong>{{ __('Model Selection:') }}</strong> {{ __('Newer models (like GPT-4o or Claude 3.5 Sonnet) provide better accuracy but may be more expensive.') }}
                        </p>
                    </li>
                    <li class="d-flex align-items-start cg-10">
                        <i class="fa-solid fa-circle-info mt-4 text-main-color fs-12"></i>
                        <p class="fs-13 text-para-text">
                            <strong>{{ __('Agent Behavior:') }}</strong> {{ __('To change how the agent talks or its knowledge base, go to') }}
                            <a href="{{ route('admin.ai-agent.knowledge') }}" class="text-main-color fw-600 underline">{{ __('Agent Knowledge') }}</a>.
                        </p>
                    </li>
                </ul>
            </div>
        </div>

    </div>
    </form>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/custom/js/ai-agent.js') }}"></script>
@endpush
