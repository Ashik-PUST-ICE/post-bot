(function ($) {
    "use strict";

    // Routes & labels come from hidden inputs in config.blade.php
    var saveRoute = $('#mailSaveRoute').val();
    var testRoute = $('#mailTestRoute').val();
    var labels    = {
        saving:         $('#lblSaving').val(),
        saveConfig:     $('#lblSaveConfig').val(),
        sending:        $('#lblSending').val(),
        send:           $('#lblSend').val(),
        serverError:    $('#lblServerError').val(),
        enterTestEmail: $('#lblEnterTestEmail').val(),
    };

    // ── Save Config ────────────────────────────────────────────────────────────
    $('#mailConfigForm').on('submit', function (e) {
        e.preventDefault();
        var $btn = $('#saveMailBtn');
        $btn.html('<i class="fa-solid fa-spinner fa-spin me-6"></i>' + labels.saving).prop('disabled', true);

        commonAjax('POST', saveRoute,
            function (res) {
                if (res.status) { toastr.success(res.message); }
                else            { toastr.error(res.message); }
                $btn.html('<i class="fa-solid fa-save me-6"></i>' + labels.saveConfig).prop('disabled', false);
            },
            function () {
                toastr.error(labels.serverError);
                $btn.html('<i class="fa-solid fa-save me-6"></i>' + labels.saveConfig).prop('disabled', false);
            },
            $('#mailConfigForm').serialize()
        );
    });

    // ── Send Test Email ────────────────────────────────────────────────────────
    $('#sendTestBtn').on('click', function () {
        var email = $('#testEmailInput').val().trim();
        if (!email) { toastr.warning(labels.enterTestEmail); return; }

        var $btn = $(this);
        $btn.html('<i class="fa-solid fa-spinner fa-spin me-5"></i>' + labels.sending).prop('disabled', true);

        commonAjax('POST', testRoute,
            function (res) {
                if (res.status) { toastr.success(res.message); }
                else            { toastr.error(res.message); }
                $btn.html('<i class="fa-solid fa-paper-plane me-5"></i>' + labels.send).prop('disabled', false);
            },
            function () {
                toastr.error(labels.serverError);
                $btn.html('<i class="fa-solid fa-paper-plane me-5"></i>' + labels.send).prop('disabled', false);
            },
            { _token: $('meta[name="csrf-token"]').attr('content'), test_email: email }
        );
    });

    // ── Toggle Password Visibility ─────────────────────────────────────────────
    $('#togglePw').on('click', function () {
        var $inp = $('#mailPassword');
        var $ico = $('#pwIcon');
        if ($inp.attr('type') === 'password') {
            $inp.attr('type', 'text');
            $ico.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            $inp.attr('type', 'password');
            $ico.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

})(jQuery);
