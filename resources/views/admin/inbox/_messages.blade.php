{{--
    Partial: admin/inbox/_messages.blade.php
    Rendered server-side and returned as JSON HTML by InboxController::getMessages().
    Variables: $messages (Collection), $conversation (Conversation)
--}}
@forelse($messages as $msg)
    @if($msg->direction == MESSAGE_DIRECTION_INBOUND)
        {{-- ── Inbound (customer) ── --}}
        <div class="d-flex align-items-end cg-10">
            <div class="wh-34 bd-ra-50 flex-shrink-0 d-flex align-items-center justify-content-center"
                style="background:{{ platformColors($conversation->platform_type) }}1a;">
                <i class="{{ platformIcons($conversation->platform_type) }} fs-14"
                    style="color:{{ platformColors($conversation->platform_type) }}"></i>
            </div>
            <div style="max-width:65%;">
                <div class="bd-one bd-c-stroke bd-ra-10 p-12 bg-body">
                    <p class="fs-14 fw-400 text-textBlack" style="white-space:pre-wrap;">{{ $msg->body }}</p>
                </div>
                <p class="fs-11 text-para-text mt-5">
                    {{ $msg->sent_at ? $msg->sent_at->format('M d, g:i A') : '' }}
                </p>
            </div>
        </div>
    @else
        {{-- ── Outbound (AI / Human) ── --}}
        <div class="d-flex align-items-end justify-content-end cg-10">
            <div style="max-width:65%;">
                <div class="bd-ra-10 p-12 {{ $msg->sender_type == MESSAGE_SENDER_AI ? 'bg-main-color' : 'bg-textBlack' }}">
                    <p class="fs-14 fw-400 text-white" style="white-space:pre-wrap;">{{ $msg->body }}</p>
                </div>
                <div class="d-flex align-items-center justify-content-end cg-5 mt-5">
                    @if($msg->sender_type == MESSAGE_SENDER_AI)
                        <i class="fa-solid fa-robot fs-11 text-para-text"></i>
                        <span class="fs-11 text-para-text">{{ __('AI Agent') }}</span>
                    @else
                        <i class="fa-solid fa-user fs-11 text-para-text"></i>
                        <span class="fs-11 text-para-text">{{ __('You') }}</span>
                    @endif
                    <span class="fs-11 text-para-text">
                        · {{ $msg->sent_at ? $msg->sent_at->format('g:i A') : '' }}
                        @if($msg->status == MESSAGE_STATUS_FAILED)
                            <span class="text-danger ms-4" title="{{ __('Delivery failed') }}">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </span>
                        @endif
                    </span>
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
