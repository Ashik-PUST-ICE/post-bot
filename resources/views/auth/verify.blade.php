@extends('auth.layouts.app')

@push('title')
{{ __('Verify') }}
@endpush

@section('content')
<div class="signLog-section">
    <div class="signLog-section-wrap">
        <div class="left" data-background="{{ asset('assets/images/auth-img-bg.png') }}" data-aos="fade-left"
            data-aos-duration="1000">
            <div class="wrap">
                <div class="zMain-signLog-content">
                    <form id="email_verify_resend_form" method="POST" action="{{ route('email.verify.resend', $token) }}" class="d-none" aria-hidden="true">
                        @csrf
                    </form>
                    <!-- Logo -->
                    <a href="{{ route('frontend') }}" class="d-flex mb-30">
                        <img src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}" class="auth-logo" />
                    </a>
                    <div class="pb-30">
                        <h4 class="fs-32 fw-600 lh-48 text-textBlack pb-5">{{ __('Verify Your Account') }}</h4>
                        <p class="fs-14 fw-400 lh-22 text-para-text">{{ __('Enter 4 digit code to') }}
                            <span>{{ $user->email }}</span>
                        </p>
                    </div>
                    @if (session('success'))
                    <p class="fs-12 fw-400 lh-20 text-success pb-10">{{ session('success') }}</p>
                    @endif
                    @if (session('error'))
                    <p class="fs-12 fw-400 lh-20 text-danger pb-10">{{ session('error') }}</p>
                    @endif
                    <form action="{{ route('email.verified', $token) }}" class="otp-form" name="otp-form" method="POST">
                        @csrf
                        <div class="otp-input-fields" id="otp-block">
                            <input type="text" name="otp__field__1" id="otp__field__1" class="otp__digit otp__field__1" maxlength="1" required />
                            <input type="text" name="otp__field__2" id="otp__field__2" class="otp__digit otp__field__2" maxlength="1" required />
                            <input type="text" name="otp__field__3" id="otp__field__3" class="otp__digit otp__field__3" maxlength="1" required />
                            <input type="text" name="otp__field__4" id="otp__field__4" class="otp__digit otp__field__4" maxlength="1" required />
                        </div>
                        <p class="fs-12 fw-400 lh-20 text-707070 pt-10 pb-25">
                            <span id="countdown_wrap">
                                {{ __('New code available in') }} <span id="countDownTime">0m 0s</span>
                            </span>
                            <span id="resend_wrap" class="d-none">
                                <button type="submit" form="email_verify_resend_form" class="border-0 bg-transparent p-0 fs-12 fw-600 lh-20 text-main-color text-decoration-underline">{{ __('Send the code again') }}</button>
                            </span>
                        </p>
                        <button type="submit"
                            class="border-0 d-flex justify-content-center align-items-center w-100 p-15 bd-ra-4 bg-main-color fs-14 fw-700 lh-20 text-white">{{
                            __('Next') }}</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="right" data-background="{{ getSettingImage('login_left_image') }}" data-aos="fade-right"
            data-aos-duration="1000">
            <div class="content">
                <h4 class="title">
                    {{ getOption('auth_page_title') }}<br />
{{--                    <span>{{ getOption('app_name') }}</span>.--}}
                </h4>
                <p class="info">{{ getOption('auth_page_description') }}</p>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="{{ $seconds_remaining }}" id="seconds_remaining">
@endsection

@push('script')
<script>
    $(document).ready(function() {
        var secondsLeft = parseInt($('#seconds_remaining').val(), 10);
        if (isNaN(secondsLeft)) {
            secondsLeft = 0;
        }
        var $countdownWrap = $('#countdown_wrap');
        var $resendWrap = $('#resend_wrap');
        var $countDownTime = $('#countDownTime');

        function renderTime(secs) {
            var m = Math.floor(secs / 60);
            var s = secs % 60;
            $countDownTime.text(m + 'm ' + s + 's');
        }

        function showResend() {
            clearInterval(window._otpCountdownInterval);
            $countdownWrap.addClass('d-none');
            $resendWrap.removeClass('d-none');
        }

        if (secondsLeft <= 0) {
            showResend();
        } else {
            renderTime(secondsLeft);
            window._otpCountdownInterval = setInterval(function() {
                secondsLeft -= 1;
                if (secondsLeft <= 0) {
                    showResend();
                } else {
                    renderTime(secondsLeft);
                }
            }, 1000);
        }
    });
    $.fn.OTPInput = function () {
        return this.each(function () {
            var inputs = $(this).find('input.otp__digit');
            inputs.each(function (i) {
                var $inp = $(this);
                function moveToNext() {
                    if (i < inputs.length - 1) {
                        inputs.eq(i + 1).focus();
                    }
                }
                function digitFromEvent(event) {
                    if (/^[0-9]$/.test(event.key)) {
                        return event.key;
                    }
                    var c = event.keyCode || event.which;
                    if (c >= 48 && c <= 57) {
                        return String(c - 48);
                    }
                    if (c >= 96 && c <= 105) {
                        return String(c - 96);
                    }
                    return null;
                }
                $inp.on('keydown', function (event) {
                    if (event.key === 'Backspace') {
                        if ($inp.val() === '' && i > 0) {
                            inputs.eq(i - 1).val('').focus();
                        } else {
                            $inp.val('');
                        }
                        event.preventDefault();
                        return;
                    }
                    var d = digitFromEvent(event);
                    if (d !== null) {
                        $inp.val(d);
                        moveToNext();
                        event.preventDefault();
                        return;
                    }
                    if (event.key === 'Tab' || event.key === 'Enter' || event.key === 'Escape') {
                        return;
                    }
                    if (event.key.length === 1 && !event.ctrlKey && !event.metaKey && !event.altKey) {
                        event.preventDefault();
                    }
                });
                $inp.on('input', function () {
                    var v = $inp.val().replace(/\D/g, '');
                    if (!v) {
                        return;
                    }
                    if (v.length === 1) {
                        $inp.val(v);
                        moveToNext();
                    } else {
                        for (var j = 0; j < v.length && (i + j) < inputs.length; j++) {
                            inputs.eq(i + j).val(v.charAt(j));
                        }
                        var next = Math.min(i + v.length, inputs.length - 1);
                        inputs.eq(next).focus();
                    }
                });
                $inp.on('paste', function (event) {
                    event.preventDefault();
                    var text = (event.originalEvent || event).clipboardData.getData('text') || '';
                    var digits = text.replace(/\D/g, '');
                    if (!digits) {
                        return;
                    }
                    for (var j = 0; j < digits.length && (i + j) < inputs.length; j++) {
                        inputs.eq(i + j).val(digits.charAt(j));
                    }
                    var next = Math.min(i + digits.length, inputs.length - 1);
                    inputs.eq(next).focus();
                });
            });
        });
    };

    $(document).ready(function () {
        $('#otp-block').OTPInput();
    });
</script>
@endpush
