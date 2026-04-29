@extends('admin.layouts.app')
@push('title'){{ $title }}@endpush

@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap g-10 pb-26">
        <div>
            <h4 class="fs-24 fw-600 lh-29 text-textBlack">{{ __('Agent Knowledge') }}</h4>
            <p class="fs-14 fw-400 text-para-text mt-5">{{ __('Train your AI Agent with business info, prompts and keyword rules.') }}</p>
        </div>
    </div>

    <form class="ajax" action="{{ route('admin.ai-agent.update') }}" method="POST" data-handler="commonResponse">
    @csrf
    {{-- Hidden fields to preserve technical config --}}
    <input type="hidden" name="ai_provider" value="{{ $settings->ai_provider }}">
    <input type="hidden" name="ai_model" value="{{ $settings->ai_model }}">
    <input type="hidden" name="language_mode" value="{{ $settings->language_mode }}">
    <input type="hidden" name="reply_delay_seconds" value="{{ $settings->reply_delay_seconds }}">
    <input type="hidden" name="max_tokens" value="{{ $settings->max_tokens }}">
    <input type="hidden" name="confidence_threshold" value="{{ $settings->confidence_threshold }}">
    <input type="hidden" name="auto_reply_enabled" value="{{ $settings->auto_reply_enabled }}">
    <input type="hidden" name="sentiment_analysis" value="{{ $settings->sentiment_analysis }}">
    <input type="hidden" name="smart_suggestions" value="{{ $settings->smart_suggestions }}">
    <input type="hidden" name="spam_detection" value="{{ $settings->spam_detection }}">
    <input type="hidden" name="conversation_memory" value="{{ $settings->conversation_memory }}">

    <div class="row rg-20">
        {{-- LEFT: Knowledge Base --}}
        <div class="col-xl-8">
            <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25 mb-20">
                <div class="row rg-20">
                    <div class="col-md-12">
                        <label class="zForm-label">{{ __('System Prompt') }}</label>
                        <textarea name="system_prompt" rows="8" class="form-control zForm-control"
                            placeholder="{{ __('You are a helpful business assistant...') }}">{{ $settings->system_prompt }}</textarea>
                        <p class="fs-12 text-para-text mt-5">{{ __('Defines the personality and core rules for the AI Agent.') }}</p>
                    </div>
                    <div class="col-md-12">
                        <label class="zForm-label">{{ __('Business Context & FAQ') }}</label>
                        <textarea name="business_context" rows="12" class="form-control zForm-control"
                            placeholder="{{ __('FAQs, product info, business hours, tone guidelines...') }}">{{ $settings->business_context }}</textarea>
                        <p class="fs-12 text-para-text mt-5">{{ __('Provide all necessary details about your business here.') }}</p>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-25">
                    <button type="submit" class="py-13 px-25 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600">
                        <i class="fa-solid fa-save me-6"></i> {{ __('Save Knowledge') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- RIGHT: Keyword Rules --}}
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
                                    <div class="d-flex align-items-center flex-wrap cg-4 mb-6">
                                        <span class="py-3 px-9 bd-ra-50 bg-body fs-11 fw-600 text-textBlack">
                                            {{ keywordMatchTypes($rule->match_type) }}
                                        </span>
                                        <span class="py-3 px-9 bd-ra-50 fs-11 fw-600 text-white"
                                            style="background:{{ keywordActionColor($rule->action ?? KEYWORD_ACTION_REPLY) }}">
                                            {{ keywordActionLabel($rule->action ?? KEYWORD_ACTION_REPLY) }}
                                        </span>
                                        @if($rule->use_ai)
                                            <span class="py-3 px-9 bd-ra-50 fs-11 fw-600" style="background:#6366f11a;color:#6366f1;">
                                                <i class="fa-solid fa-robot me-3"></i>AI
                                            </span>
                                        @endif
                                    </div>
                                    <p class="fs-14 fw-600 text-textBlack text-truncate">"{{ $rule->keyword }}"</p>
                                    @if(($rule->action ?? KEYWORD_ACTION_REPLY) === KEYWORD_ACTION_REPLY)
                                        <p class="fs-12 text-para-text mt-3 text-truncate">{{ $rule->reply_template }}</p>
                                    @endif
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
                        <label class="zForm-label">{{ __('Action') }}</label>
                        <select name="action" id="keywordAction" class="form-control zForm-control">
                            @foreach(keywordActionLabels() as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="replyTemplateWrap">
                        <label class="zForm-label">{{ __('Reply Template') }} <span class="text-red">*</span></label>
                        <textarea name="reply_template" rows="4" class="form-control zForm-control"
                            placeholder="{{ __('Enter the reply message... Use {customer_name}, {business_name}, {platform}') }}"></textarea>
                    </div>
                    <div class="d-flex align-items-center justify-content-between bd-one bd-c-stroke bd-ra-8 p-12" id="useAiWrap">
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
