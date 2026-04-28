@extends('admin.layouts.app')
@push('title')
    {{ $title }}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between flex-wrap g-10 pb-20">
            <div class="d-flex align-items-center cg-10">
                <a href="{{ route('admin.inbox.index') }}" class="text-para-text fs-20">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h4 class="fs-18 fw-600 text-textBlack">
                        {{ $conversation->contact_name ?? __('Unknown Contact') }}
                    </h4>
                    <p class="fs-13 text-para-text">
                        <i class="{{ platformIcons($conversation->platform_type) }}"
                           style="color:{{ platformColors($conversation->platform_type) }}"></i>
                        {{ platformTypes($conversation->platform_type) }}
                        @if($conversation->platformConnection)
                            · {{ $conversation->platformConnection->platform_name }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="d-flex align-items-center cg-10">
                <select class="form-control zForm-control" id="conversationStatusSelect"
                    data-id="{{ $conversation->id }}"
                    data-route="{{ route('admin.inbox.update.status', $conversation->id) }}">
                    @foreach(conversationStatuses() as $val => $label)
                        <option value="{{ $val }}" {{ $conversation->status == $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row rg-20">
            {{-- Chat Thread --}}
            <div class="col-xl-9">
                <div class="bd-one bd-c-stroke bd-ra-10 bg-white overflow-hidden d-flex flex-column"
                    style="min-height:500px;">

                    {{-- Messages --}}
                    <div class="flex-grow-1 p-20 d-flex flex-column rg-12" id="messageThread"
                        style="overflow-y:auto; max-height:500px;">

                        @forelse($messages as $msg)
                            @if($msg->direction == MESSAGE_DIRECTION_INBOUND)
                                {{-- Inbound (customer) --}}
                                <div class="d-flex align-items-end cg-10">
                                    <div class="wh-34 bd-ra-50 flex-shrink-0 d-flex align-items-center justify-content-center"
                                        style="background:{{ platformColors($conversation->platform_type) }}1a;">
                                        <i class="{{ platformIcons($conversation->platform_type) }} fs-14"
                                            style="color:{{ platformColors($conversation->platform_type) }}"></i>
                                    </div>
                                    <div style="max-width:65%;">
                                        <div class="bd-one bd-c-stroke bd-ra-10 p-12 bg-body">
                                            <p class="fs-14 fw-400 text-textBlack">{{ $msg->body }}</p>
                                        </div>
                                        <p class="fs-11 text-para-text mt-5">
                                            {{ $msg->sent_at ? $msg->sent_at->format('M d, g:i A') : '' }}
                                        </p>
                                    </div>
                                </div>
                            @else
                                {{-- Outbound (AI or Human) --}}
                                <div class="d-flex align-items-end justify-content-end cg-10">
                                    <div style="max-width:65%;">
                                        <div class="bd-ra-10 p-12 {{ $msg->sender_type == MESSAGE_SENDER_AI ? 'bg-main-color' : 'bg-textBlack' }}">
                                            <p class="fs-14 fw-400 text-white">{{ $msg->body }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-end cg-5 mt-5">
                                            @if($msg->sender_type == MESSAGE_SENDER_AI)
                                                <i class="fa-solid fa-robot fs-11 text-para-text"></i>
                                                <span class="fs-11 text-para-text">{{ __('AI Agent') }}</span>
                                            @else
                                                <i class="fa-solid fa-user fs-11 text-para-text"></i>
                                                <span class="fs-11 text-para-text">{{ __('You') }}</span>
                                            @endif
                                            <span class="fs-11 text-para-text">· {{ $msg->sent_at ? $msg->sent_at->format('g:i A') : '' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="text-center py-50">
                                <i class="fa-regular fa-comment-dots fs-36 text-para-text"></i>
                                <p class="fs-14 text-para-text mt-10">{{ __('No messages in this conversation yet.') }}</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Reply Box --}}
                    <div class="bd-t-one bd-c-stroke p-15">
                        <form class="ajax" action="{{ route('admin.inbox.reply', $conversation->id) }}"
                            method="POST" data-handler="commonResponse" id="replyForm">
                            @csrf
                            <div class="d-flex align-items-end cg-10">
                                <textarea name="body" id="replyBody" rows="3"
                                    class="form-control zForm-control flex-grow-1"
                                    placeholder="{{ __('Type your reply...') }}" style="resize:none;"></textarea>
                                <button type="submit"
                                    class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 flex-shrink-0">
                                    <i class="fa-solid fa-paper-plane me-5"></i> {{ __('Send') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Sidebar Info --}}
            <div class="col-xl-3">
                <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-20 d-flex flex-column rg-15">
                    <h5 class="fs-16 fw-600 text-textBlack">{{ __('Contact Info') }}</h5>
                    <div class="d-flex flex-column rg-10">
                        <div>
                            <p class="fs-12 fw-500 text-para-text text-uppercase">{{ __('Name') }}</p>
                            <p class="fs-14 fw-600 text-textBlack">{{ $conversation->contact_name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="fs-12 fw-500 text-para-text text-uppercase">{{ __('Platform ID') }}</p>
                            <p class="fs-13 fw-400 text-textBlack">{{ $conversation->contact_id ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="fs-12 fw-500 text-para-text text-uppercase">{{ __('AI Replied') }}</p>
                            <p class="fs-13 fw-400">
                                @if($conversation->ai_replied)
                                    <span class="text-success"><i class="fa-solid fa-check me-4"></i> {{ __('Yes') }}</span>
                                @else
                                    <span class="text-para-text">{{ __('No') }}</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="fs-12 fw-500 text-para-text text-uppercase">{{ __('Human Taken Over') }}</p>
                            <p class="fs-13 fw-400">
                                @if($conversation->human_taken_over)
                                    <span class="text-warning"><i class="fa-solid fa-user me-4"></i> {{ __('Yes') }}</span>
                                @else
                                    <span class="text-para-text">{{ __('No') }}</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="fs-12 fw-500 text-para-text text-uppercase">{{ __('Total Messages') }}</p>
                            <p class="fs-14 fw-600 text-textBlack">{{ $messages->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="convId" value="{{ $conversation->id }}">
    <input type="hidden" id="statusRoute" value="{{ route('admin.inbox.update.status', $conversation->id) }}">
@endsection

@push('script')
<script>
    // Scroll to bottom of thread
    var thread = document.getElementById('messageThread');
    if (thread) thread.scrollTop = thread.scrollHeight;

    // Status change
    $('#conversationStatusSelect').on('change', function () {
        $.post($(this).data('route'), {
            _token: '{{ csrf_token() }}',
            status: $(this).val()
        }, function (res) {
            if (res.status) toastr.success(res.message);
            else toastr.error(res.message);
        });
    });

    // After reply sent, reload to show new message
    $(document).on('ajaxSuccess', function (e, res) {
        if (res.status && $('#replyForm').length) {
            setTimeout(function () { window.location.reload(); }, 800);
        }
    });
</script>
@endpush
