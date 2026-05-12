(function ($) {
    "use strict";

    /**
     * Handle "Connect This Page/Number/Account" button clicks on the OAuth picker page.
     *
     * Why we bypass commonAjax():
     *  commonAjax() sets contentType:false + processData:false for every POST, which
     *  is designed for multipart FormData uploads. Laravel's VerifyCsrfToken middleware
     *  can struggle to read _token from multipart bodies in some environments.
     *
     *  Using a direct $.ajax() call with:
     *    - X-CSRF-TOKEN header  (always read by Laravel regardless of content-type)
     *    - standard urlencoded body
     *  is the safest, guaranteed approach.
     */
    $(document).on('click', '.connect-oauth-btn', function () {
        var btn           = $(this);
        var route         = btn.data('route');
        var pageId        = btn.data('page-id');
        var pageName      = btn.data('page-name');
        var accessToken   = btn.data('access-token');
        var platformType  = btn.data('platform-type');
        var phoneNumberId = btn.data('phone-number-id') || '';
        var igUserId      = btn.data('ig-user-id') || '';
        var csrfToken     = $('meta[name="csrf-token"]').attr('content');

        // Log what we are about to send (helps debug 419 / missing-field issues)
        console.group('[meta-oauth] Connect button clicked');
        console.log('route        :', route);
        console.log('pageId       :', pageId);
        console.log('pageName     :', pageName);
        console.log('platformType :', platformType);
        console.log('phoneNumberId:', phoneNumberId);
        console.log('igUserId     :', igUserId);
        console.log('csrfToken    :', csrfToken ? csrfToken.substring(0, 12) + '…' : '⚠ MISSING');
        console.groupEnd();

        if (!csrfToken) {
            toastr.error('CSRF token missing. Please refresh the page and try again.');
            return;
        }

        // Visual loading state
        var origHtml = btn.html();
        btn.html('<i class="fa-solid fa-spinner fa-spin me-6"></i>Connecting...').prop('disabled', true);

        $.ajax({
            type       : 'POST',
            url        : route,
            dataType   : 'json',
            // X-CSRF-TOKEN header is read by Laravel *before* it tries to parse
            // the body, so this works regardless of Content-Type.
            headers    : { 'X-CSRF-TOKEN': csrfToken },
            data       : {
                page_id        : pageId,
                page_name      : pageName,
                access_token   : accessToken,
                platform_type  : platformType,
                phone_number_id: phoneNumberId,
                ig_user_id     : igUserId,
            },
            success: function (res) {
                console.log('[meta-oauth] savePage response:', res);
                if (res.status) {
                    toastr.success(res.message);
                    setTimeout(function () {
                        window.location.href = res.redirect;
                    }, 800);
                } else {
                    toastr.error(res.message);
                    btn.html(origHtml).prop('disabled', false);
                }
            },
            error: function (xhr) {
                console.error('[meta-oauth] savePage HTTP error:', xhr.status, xhr.responseText);
                var msg = 'Connection failed (HTTP ' + xhr.status + ').';
                if (xhr.status === 419) {
                    msg = 'Session expired (419). Please refresh the page and try again.';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                toastr.error(msg);
                btn.html(origHtml).prop('disabled', false);
            }
        });
    });

})(jQuery);
