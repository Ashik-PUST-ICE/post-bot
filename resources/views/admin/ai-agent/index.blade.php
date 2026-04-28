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
            <h4 class="fs-24 fw-600 lh-29 text-textBlack">{{ __('AI Agent Configuration') }}</h4>
            <p class="fs-14 fw-400 text-para-text mt-5">{{ __('Choose your AI provider, set API keys and tune behaviour.') }}</p>
        </div>
    </div>

    <form class="ajax" action="{{ route('admin.ai-agent.update') }}" method="POST" data-handler="commonResponse">
    @csrf

    <div class="row rg-20">

        {{-- ── LEFT COLUMN ──────────────────────────────────────────────── --}}
        <div class="col-xl-8">

            {{-- Provider Cards --}}
            <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25 mb-20">
                <h5 class="fs-16 fw-600 text-textBlack pb-18 bd-b-one bd-c-stroke mb-20">
                    {{ __('Select AI Provider') }}
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
                        <label class="provider-card d-flex align-items-center cg-12 bd-one bd-ra-10 p-15 cursor-pointer w-100"
                            style="border-color:{{ $isActive ? $color : '#e5e7eb' }};
                                   background:{{ $isActive ? $color.'12' : '#fff' }};
                                   transition:all .2s;">
                            <input type="radio" name="ai_provider" value="{{ $providerKey }}"
                                class="d-none provider-radio" {{ $isActive ? 'checked' : '' }}>
                            <div class="wh-36 bd-ra-8 d-flex align-items-center justify-content-center flex-shrink-0"
                                style="background:{{ $color }}1a;">
                                <i class="{{ $providerIcons[$providerKey] }} fs-18" style="color:{{ $color }}"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="fs-13 fw-600 text-textBlack text-truncate">{{ $providerLabel }}</p>
                                @if($hasKey)
                                    <span class="fs-11 fw-600" style="color:#10b981;">
                                        <i class="fa-solid fa-circle-check me-3 fs-10"></i>{{ __('Key saved') }}
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
            <div class="provider-key-panel bd-one bd-c-stroke bd-ra-10 bg-white p-25 mb-20 {{ $settings->ai_provider === $providerKey ? '' : 'd-none' }}"
                 id="panel-{{ $providerKey }}">
                <div class="d-flex align-items-center justify-content-between pb-18 bd-b-one bd-c-stroke mb-20">
                    <div class="d-flex align-items-center cg-10">
                        <div class="wh-36 bd-ra-8 d-flex align-items-center justify-content-center" style="background:{{ $color }}1a;">
                            <i class="{{ $providerIcons[$providerKey] }} fs-18" style="color:{{ $color }}"></i>
                        </div>
                        <h5 class="fs-16 fw-600 text-textBlack">{{ $providerLabel }} — {{ __('API Key') }}</h5>
                    </div>
                    <a href="{{ $providerApiDocs[$providerKey] }}" target="_blank"
                        class="fs-12 text-main-color d-flex align-items-center cg-5">
                        {{ __('Get API Key') }} <i class="fa-solid fa-arrow-up-right-from-square fs-10 ms-4"></i>
                    </a>
                </div>
                <div class="row rg-15">
                    <div class="col-md-12">
                        <label class="zForm-label">
                            {{ __('API Key') }}
                            <span class="fs-11 text-para-text ms-5">{{ __('(leave blank to keep existing)') }}</span>
                        </label>
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
                        <label class="zForm-label">{{ __('Select Model') }}</label>
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

            {{-- Prompt & Context --}}
            <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25 mb-20">
                <h5 class="fs-16 fw-600 text-textBlack pb-18 bd-b-one bd-c-stroke mb-20">
                    {{ __('Prompt & Context') }}
                </h5>
                <div class="row rg-20">
                    <div class="col-md-12">
                        <label class="zForm-label">{{ __('System Prompt') }}</label>
                        <textarea name="system_prompt" rows="4" class="form-control zForm-control"
                            placeholder="{{ __('You are a helpful business assistant...') }}">{{ $settings->system_prompt }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="zForm-label">{{ __('Business Context') }}</label>
                        <textarea name="business_context" rows="6" class="form-control zForm-control"
                            placeholder="{{ __('FAQs, product info, business hours, tone guidelines...') }}">{{ $settings->business_context }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="zForm-label">{{ __('Reply Language') }}</label>
                        <select name="language_mode" class="form-control zForm-control">
                            @foreach(aiLanguageModes() as $val => $label)
                                <option value="{{ $val }}" {{ $settings->language_mode == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="zForm-label">{{ __('Reply Delay (s)') }}</label>
                        <input type="number" name="reply_delay_seconds" min="0" max="60"
                            class="form-control zForm-control" value="{{ $settings->reply_delay_seconds }}">
                    </div>
                    <div class="col-md-3">
                        <label class="zForm-label">{{ __('Max Tokens') }}</label>
                        <input type="number" name="max_tokens" min="64" max="4096"
                            class="form-control zForm-control" value="{{ $settings->max_tokens ?? 512 }}">
                    </div>
                    <div class="col-md-6">
                        <label class="zForm-label">{{ __('Confidence Threshold (%)') }}</label>
                        <input type="number" name="confidence_threshold" min="0" max="100"
                            class="form-control zForm-control" value="{{ $settings->confidence_threshold }}">
                        <p class="fs-12 text-para-text mt-5">{{ __('Below this → escalate to human.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Feature Toggles --}}
            <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25 mb-20">
                <h5 class="fs-16 fw-600 text-textBlack pb-18 bd-b-one bd-c-stroke mb-20">
                    {{ __('Feature Toggles') }}
                </h5>
                @php
                    $toggles = [
                        ['name' => 'auto_reply_enabled',  'label' => __('Auto Reply'),          'desc' => __('AI replies automatically to incoming messages.'),          'val' => $settings->auto_reply_enabled],
                        ['name' => 'sentiment_analysis',  'label' => __('Sentiment Analysis'),  'desc' => __('Detect customer mood and adjust tone accordingly.'),        'val' => $settings->sentiment_analysis],
                        ['name' => 'smart_suggestions',   'label' => __('Smart Suggestions'),   'desc' => __('Suggest replies for human approval before sending.'),       'val' => $settings->smart_suggestions],
                        ['name' => 'spam_detection',      'label' => __('Spam Detection'),      'desc' => __('Automatically ignore spam messages.'),                     'val' => $settings->spam_detection],
                        ['name' => 'conversation_memory', 'label' => __('Conversation Memory'), 'desc' => __('Include past messages in context window for continuity.'),  'val' => $settings->conversation_memory],
                    ];
                @endphp
                <div class="d-flex flex-column rg-12">
                    @foreach($toggles as $t)
                    <div class="d-flex align-items-center justify-content-between bd-one bd-c-stroke bd-ra-8 p-15">
                        <div>
                            <p class="fs-14 fw-600 text-textBlack">{{ $t['label'] }}</p>
                            <p class="fs-13 text-para-text mt-3">{{ $t['desc'] }}</p>
                        </div>
                        <div class="zCheck form-check form-switch flex-shrink-0 ms-15">
                            <input class="form-check-input" type="checkbox" name="{{ $t['name'] }}"
                                value="1" role="switch" {{ $t['val'] == STATUS_ACTIVE ? 'checked' : '' }}>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="d-flex justify-content-end pb-20">
                <button type="submit" class="py-13 px-25 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">
                    <i class="fa-solid fa-save me-6"></i> {{ __('Save Configuration') }}
                </button>
            </div>
        </div>

        {{-- ── RIGHT COLUMN — Keyword Rules ────────────────────────────── --}}
        <div class="col-xl-4">
            <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25">
                <div class="d-flex align-items-center justify-content-between pb-18 bd-b-one bd-c-stroke mb-20">
                    <h5 class="fs-16 fw-600 text-textBlack">{{ __('Keyword Rules') }}</h5>
                    <button type="button"
                        class="py-8 px-14 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-13 fw-500 text-white"
                        data-bs-toggle="modal" data-bs-target="#addKeywordModal">
                        <i class="fa fa-plus me-4"></i> {{ __('Add') }}
                    </button>
                </div>
                @if($keywordRules->isEmpty())
                    <div class="text-center py-30">
                        <i class="fa-solid fa-key fs-30 text-para-text mb-10"></i>
                        <p class="fs-13 text-para-text">{{ __('No keyword rules yet.') }}</p>
                    </div>
                @else
                    <div class="d-flex flex-column rg-10">
                        @foreach($keywordRules as $rule)
                        <div class="bd-one bd-c-stroke bd-ra-8 p-14">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex align-items-center cg-6 mb-6">
                                        <span class="py-3 px-9 bd-ra-50 bg-body fs-11 fw-600 text-textBlack">
                                            {{ keywordMatchTypes($rule->match_type) }}
                                        </span>
                                        @if($rule->use_ai)
                                            <span class="py-3 px-9 bd-ra-50 fs-11 fw-600" style="background:#6366f11a;color:#6366f1;">
                                                <i class="fa-solid fa-robot me-3"></i>AI
                                            </span>
                                        @endif
                                    </div>
                                    <p class="fs-14 fw-600 text-textBlack text-truncate">"{{ $rule->keyword }}"</p>
                                    <p class="fs-12 text-para-text mt-3 text-truncate">{{ $rule->reply_template }}</p>
                                </div>
                                <button type="button" class="border-0 bg-transparent ms-10 delete-keyword-btn"
                                    data-route="{{ route('admin.ai-agent.keyword.destroy', $rule->id) }}">
                                    <i class="fa-solid fa-trash fs-13 text-red"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
    </form>
</div>

{{-- Add Keyword Modal --}}
<div class="modal fade" id="addKeywordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-20">
            <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                <h4 class="fs-18 fw-600 text-textBlack">{{ __('Add Keyword Rule') }}</h4>
                <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <form class="ajax reset" action="{{ route('admin.ai-agent.keyword.store') }}" method="POST"
                data-handler="commonResponse">
                @csrf
                <div class="d-flex flex-column rg-15 pb-20">
                    <div>
                        <label class="zForm-label">{{ __('Keyword / Phrase') }} <span class="text-red">*</span></label>
                        <input type="text" name="keyword" class="form-control zForm-control"
                            placeholder="{{ __('e.g. price, delivery, refund') }}">
                    </div>
                    <div>
                        <label class="zForm-label">{{ __('Match Type') }}</label>
                        <select name="match_type" class="form-control zForm-control">
                            @foreach(keywordMatchTypes() as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="zForm-label">{{ __('Platform (optional)') }}</label>
                        <select name="platform_connection_id" class="form-control zForm-control">
                            <option value="">{{ __('All Platforms') }}</option>
                            @foreach($platforms as $p)
                                <option value="{{ $p->id }}">{{ platformTypes($p->platform_type) }} — {{ $p->platform_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="zForm-label">{{ __('Reply Template') }} <span class="text-red">*</span></label>
                        <textarea name="reply_template" rows="4" class="form-control zForm-control"
                            placeholder="{{ __('Enter the reply message...') }}"></textarea>
                    </div>
                    <div class="d-flex align-items-center justify-content-between bd-one bd-c-stroke bd-ra-8 p-12">
                        <div>
                            <p class="fs-14 fw-600 text-textBlack">{{ __('Use AI') }}</p>
                            <p class="fs-12 text-para-text">{{ __('AI enhances the reply with context.') }}</p>
                        </div>
                        <div class="zCheck form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="use_ai" value="1" role="switch">
                        </div>
                    </div>
                </div>
                <div class="d-flex g-10 justify-content-end">
                    <button type="button" class="py-13 px-20 bd-one bd-ra-4 bd-c-body-text bg-white text-textBlack fs-14 fw-600 lh-14"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">
                        {{ __('Save Rule') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/custom/js/ai-agent.js') }}"></script>
@endpush
