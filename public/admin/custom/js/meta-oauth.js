(function ($) {
    "use strict";

    /**
     * Handle "Connect This Page/Number/Account" button clicks on the OAuth picker page.
     * Sends a POST to /admin/meta-oauth/save-page and redirects on success.
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

        // Visual loading state
        var origHtml = btn.html();
        btn.html('<i class="fa-solid fa-spinner fa-spin me-6"></i>Connecting...').prop('disabled', true);

        commonAjax('POST', route, function (res) {
            if (res.status) {
                toastr.success(res.message);
                setTimeout(function () {
                    window.location.href = res.redirect;
                }, 800);
            } else {
                toastr.error(res.message);
                btn.html(origHtml).prop('disabled', false);
            }
        }, function (xhr) {
            toastr.error('Connection failed. Please try again.');
            btn.html(origHtml).prop('disabled', false);
        }, {
            _token:          $('meta[name="csrf-token"]').attr('content'),
            page_id:         pageId,
            page_name:       pageName,
            access_token:    accessToken,
            platform_type:   platformType,
            phone_number_id: phoneNumberId,
            ig_user_id:      igUserId,
        });
    });

})(jQuery);
