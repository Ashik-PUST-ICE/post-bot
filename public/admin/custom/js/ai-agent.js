(function ($) {
    "use strict";

    // ── Provider card selection ────────────────────────────────────────────────
    var providerColors = $('#providerColorsJson').val()
        ? JSON.parse($('#providerColorsJson').val()) : {};

    $(document).on('change', '.provider-radio', function () {
        var selected = $(this).val();

        // Style all cards
        $('.provider-card').each(function () {
            var radio = $(this).find('.provider-radio');
            var pKey  = radio.val();
            var color = providerColors[pKey] || '#e5e7eb';
            if (radio.is(':checked')) {
                $(this).css({ 'border-color': color, 'background': color + '12' });
            } else {
                $(this).css({ 'border-color': '#e5e7eb', 'background': '#fff' });
            }
        });

        // Show matching key panel
        $('.provider-key-panel').addClass('d-none');
        $('#panel-' + selected).removeClass('d-none');

        // Load model list for newly selected provider
        loadModelsForProvider(selected, null);
    });

    // ── Toggle API key visibility ──────────────────────────────────────────────
    $(document).on('click', '.toggle-key-vis', function () {
        var input = $('#' + $(this).data('target'));
        var icon  = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // ── Test connection ────────────────────────────────────────────────────────
    $(document).on('click', '.test-connection-btn', function () {
        var btn      = $(this);
        var provider = btn.data('provider');
        var apiKey   = $('#key-' + provider).val();
        var result   = $('.test-result-' + provider);
        var route    = $('#testConnectionRoute').val();

        if (!apiKey) {
            result.html('<span class="text-red"><i class="fa-solid fa-circle-xmark me-4"></i>Enter an API key first.</span>');
            return;
        }

        btn.html('<i class="fa-solid fa-spinner fa-spin me-5"></i>Testing...').prop('disabled', true);
        result.html('');

        commonAjax('POST', route, function (res) {
            if (res.status) {
                result.html('<span style="color:#10b981"><i class="fa-solid fa-circle-check me-4"></i>' + res.message + '</span>');
            } else {
                result.html('<span class="text-red"><i class="fa-solid fa-circle-xmark me-4"></i>' + res.message + '</span>');
            }
            btn.html('<i class="fa-solid fa-plug-circle-check me-5"></i>Test').prop('disabled', false);
        }, function () {
            result.html('<span class="text-red">Request failed.</span>');
            btn.html('<i class="fa-solid fa-plug-circle-check me-5"></i>Test').prop('disabled', false);
        }, {
            _token:   $('meta[name="csrf-token"]').attr('content'),
            provider: provider,
            api_key:  apiKey,
            model:    $('#model-' + provider).val()
        });
    });

    // ── Dynamic model list ─────────────────────────────────────────────────────
    function loadModelsForProvider(provider, activeModel) {
        var route = $('#modelsForProviderRoute').val();
        commonAjax('GET', route, function (res) {
            if (!res.status) return;
            var select = $('#model-' + provider);
            select.empty();
            $.each(res.models, function (slug, label) {
                var selected = (activeModel && slug === activeModel) ? ' selected' : '';
                select.append('<option value="' + slug + '"' + selected + '>' + label + '</option>');
            });
        }, commonHandler, { provider: provider });
    }

    // ── Keyword rule delete ────────────────────────────────────────────────────
    $(document).on('click', '.delete-keyword-btn', function () {
        var url = $(this).data('route');
        Swal.fire({
            title: 'Delete keyword rule?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor:  '#6b7280',
            confirmButtonText:  'Yes, delete!'
        }).then(function (result) {
            if (result.isConfirmed) {
                commonAjax('POST', url, function (res) {
                    if (res.status) {
                        toastr.success(res.message);
                        setTimeout(function () { location.reload(); }, 800);
                    } else {
                        toastr.error(res.message);
                    }
                }, commonHandler, { _token: $('meta[name="csrf-token"]').attr('content') });
            }
        });
    });

    // ── Keyword action toggle (knowledge page) ─────────────────────────────────
    // Show/hide the reply textarea based on the selected action
    if ($('#keywordAction').length) {
        function toggleReplyFields() {
            if ($('#keywordAction').val() === 'reply') {
                $('#replyTemplateWrap, #useAiWrap').show();
            } else {
                $('#replyTemplateWrap, #useAiWrap').hide();
            }
        }
        $('#keywordAction').on('change', toggleReplyFields);
        toggleReplyFields();
    }

})(jQuery);
