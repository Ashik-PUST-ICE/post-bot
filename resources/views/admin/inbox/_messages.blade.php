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
                {{-- FB / IG comment context badge --}}
                @if($msg->meta_type && in_array($msg->meta_type, ['fb_comment', 'ig_comment', 'ig_mention']) && $msg->ai_metadata)
                    @php $meta = $msg->ai_metadata; @endphp
                    <div class="d-flex align-items-center cg-6 mb-6 flex-wrap">
                        @if($msg->meta_type === 'fb_comment')
                            <span class="py-2 px-8 bd-ra-50 fs-10 fw-600 d-inline-flex align-items-center cg-4"
                                style="background:#1877F21a; color:#1877F2; border:1px solid #1877F230;">
                                <i class="fa-brands fa-facebook fs-10"></i> {{ __('FB Post Comment') }}
                            </span>
                        @elseif($msg->meta_type === 'ig_comment')
                            <span class="py-2 px-8 bd-ra-50 fs-10 fw-600 d-inline-flex align-items-center cg-4"
                                style="background:#E1306C1a; color:#E1306C; border:1px solid #E1306C30;">
                                <i class="fa-brands fa-instagram fs-10"></i> {{ __('IG Post Comment') }}
                            </span>
                        @elseif($msg->meta_type === 'ig_mention')
                            <span class="py-2 px-8 bd-ra-50 fs-10 fw-600 d-inline-flex align-items-center cg-4"
                                style="background:#E1306C1a; color:#E1306C; border:1px solid #E1306C30;">
                                <i class="fa-brands fa-instagram fs-10"></i> {{ __('IG Mention') }}
                            </span>
                        @endif
                        @if(!empty($meta['post_id']))
                            <span class="fs-10 text-para-text">
                                {{ __('Post') }}: 
                                <a href="https://www.facebook.com/{{ $meta['post_id'] }}" target="_blank"
                                    class="text-main-color fw-600" style="font-size:10px;">
                                    #{{ Str::limit($meta['post_id'], 20) }}
                                    <i class="fa-solid fa-arrow-up-right-from-square fs-9 ms-2"></i>
                                </a>
                            </span>
                        @endif
                        @if(!empty($meta['parent_id']) && $meta['parent_id'] !== $meta['post_id'])
                            <span class="fs-10 text-para-text">
                                <i class="fa-solid fa-reply fs-9 me-2"></i>{{ __('Reply to comment') }}
                            </span>
                        @endif
                    </div>
                @endif
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

