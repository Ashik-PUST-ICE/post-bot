(function ($) {
    "use strict";

    // ── Copy to clipboard ──────────────────────────────────────────────────────
    $(document).on('click', '.copy-btn', function () {
        var inputId = $(this).data('copy');
        var val     = $('#' + inputId).val();
        var btn     = $(this);

        navigator.clipboard.writeText(val).then(function () {
            var orig = btn.html();
            btn.html('<i class="fa-solid fa-check me-5"></i>Copied!').css('color', '#10b981');
            setTimeout(function () { btn.html(orig).css('color', ''); }, 2000);
        });
    });

    // ── Regenerate webhook verify token ───────────────────────────────────────
    $(document).on('click', '#regenTokenBtn', function () {
        var route = $(this).data('route');
        Swal.fire({
            title: 'Regenerate Token?',
            text: "You will need to update this token in Meta App Dashboard.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6366f1',
            cancelButtonColor:  '#6b7280',
            confirmButtonText:  'Yes, regenerate!'
        }).then(function (result) {
            if (result.isConfirmed) {
                commonAjax('POST', route, function (res) {
                    if (res.status) {
                        $('#verifyTokenInput').val(res.token);
                        toastr.success(res.message);
                    } else {
                        toastr.error(res.message);
                    }
                }, commonHandler, { _token: $('meta[name="csrf-token"]').attr('content') });
            }
        });
    });

    // ── Live connection check ──────────────────────────────────────────────────
    $(document).on('click', '#checkConnectionBtn', function () {
        var btn   = $(this);
        var route = btn.data('route');

        btn.html('<i class="fa-solid fa-spinner fa-spin me-6"></i>Checking...').prop('disabled', true);
        $('.conn-badge').html('<span class="text-para-text fs-12">Checking...</span>');

        commonAjax('GET', route, function (res) {
            renderConnBadge('#fb-conn-badge',  res.facebook);
            renderConnBadge('#wa-conn-badge',  res.whatsapp);
            renderConnBadge('#ig-conn-badge',  res.instagram);
            btn.html('<i class="fa-solid fa-satellite-dish me-6"></i>Check Connection').prop('disabled', false);
        }, function () {
            btn.html('<i class="fa-solid fa-satellite-dish me-6"></i>Check Connection').prop('disabled', false);
            toastr.error('Connection check failed.');
        }, {});
    });

    function renderConnBadge(selector, result) {
        if (result.ok) {
            $(selector).html(
                '<span style="color:#10b981" class="fs-12 fw-500">' +
                '<i class="fa-solid fa-circle-check me-4"></i>' + result.message +
                '</span>'
            );
        } else {
            $(selector).html(
                '<span class="text-red fs-12 fw-500">' +
                '<i class="fa-solid fa-circle-xmark me-4"></i>' + result.message +
                '</span>'
            );
        }
    }

    // ── App Secret toggle visibility ───────────────────────────────────────────
    $(document).on('click', '#toggleSecretBtn', function () {
        var input = $('#appSecretInput');
        var icon  = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Same for all token fields
    $(document).on('click', '.toggle-token-vis', function () {
        var target = $(this).data('target');
        var input  = $('#' + target);
        var icon   = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Global helper for the onclick attribute used in Meta App tab
    window.toggleSecret = function() {
        var input = $('#appSecretInput');
        var icon  = $('#secretEyeIcon');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    };

})(jQuery);
