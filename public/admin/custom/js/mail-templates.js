(function ($) {
    "use strict";

    // ─── Config from DOM ──────────────────────────────────────────────────────
    var getRoute    = $('#getTemplateRoute').val();
    var updateRoute = $('#updateTemplateRoute').val();
    var sendRoute   = $('#sendMailRoute').val();

    var labels = {
        saving:        $('#lblSaving').val(),
        saveTemplate:  $('#lblSaveTemplate').val(),
        sending:       $('#lblSending').val(),
        sendEmail:     $('#lblSendEmail').val(),
        serverError:   $('#lblServerError').val(),
        loadFailed:    $('#lblLoadFailed').val(),
        previewDefault:$('#lblPreviewDefault').val(),
        businessName:  $('#lblBusinessName').val(),
    };

    // Human-readable names for placeholder keys  e.g. { order_id: "Order ID", amount: "Amount / Price" }
    var phLabels = {};
    try { phLabels = JSON.parse($('#phLabels').val()); } catch(e) {}

    // ─── State ────────────────────────────────────────────────────────────────
    var rawBody    = ''; // raw template body with {placeholders}
    var rawSubject = ''; // raw template subject with {placeholders}

    // ─── ① Edit Template Modal ────────────────────────────────────────────────

    $(document).on('click', '.editTemplateBtn', function () {
        var id = $(this).data('id');
        $.get(getRoute, { id: id }, function (res) {
            if (!res.status) { toastr.error(labels.loadFailed); return; }
            var t = res.data;
            $('#editTemplateId').val(t.id);
            $('#editSubject').val(t.subject);
            $('#editBody').val(t.body);
            new bootstrap.Modal($('#editTemplateModal')[0]).show();
        });
    });

    $('#editTemplateForm').on('submit', function (e) {
        e.preventDefault();
        var $btn = $('#saveTemplateBtn');
        $btn.html('<i class="fa-solid fa-spinner fa-spin me-6"></i>' + labels.saving).prop('disabled', true);

        commonAjax('POST', updateRoute,
            function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    bootstrap.Modal.getInstance($('#editTemplateModal')[0]).hide();
                    setTimeout(function () { location.reload(); }, 900);
                } else {
                    toastr.error(res.message);
                }
                $btn.html('<i class="fa-solid fa-save me-6"></i>' + labels.saveTemplate).prop('disabled', false);
            },
            function () {
                toastr.error(labels.serverError);
                $btn.html('<i class="fa-solid fa-save me-6"></i>' + labels.saveTemplate).prop('disabled', false);
            },
            $(this).serialize()
        );
    });

    // ─── ② Send Email Modal — open from card button ───────────────────────────

    $(document).on('click', '.sendTemplateBtn', function () {
        // Pre-select the template in the dropdown
        var templateId = $(this).data('id');
        $('#useTemplateSelect').val(templateId).trigger('change');
        new bootstrap.Modal($('#sendEmailModal')[0]).show();
    });

    // ─── ③ Template Picker — detect placeholders + build input fields ─────────

    $('#useTemplateSelect').on('change', function () {
        var $opt = $(this).find(':selected');
        if (!$opt.val()) {
            rawBody    = '';
            rawSubject = '';
            renderPreview();
            $('#placeholderSection').hide();
            $('#placeholderFields').empty();
            return;
        }

        rawSubject = $opt.data('subject') || '';
        rawBody    = $opt.data('body')    || '';

        $('#sendSubject').val(rawSubject);
        buildPlaceholderFields(rawBody + ' ' + rawSubject);
        renderPreview();
    });

    // When subject is manually edited — update preview
    $('#sendSubject').on('input', function () {
        rawSubject = $(this).val();
        renderPreview();
    });

    // ─── ④ Placeholder field builder ──────────────────────────────────────────

    function buildPlaceholderFields(text) {
        // Find all {placeholder} tokens in the template text
        var regex   = /\{(\w+)\}/g;
        var match;
        var found   = {};
        var ordered = [];

        while ((match = regex.exec(text)) !== null) {
            var key = match[1];
            if (!found[key]) {
                found[key] = true;
                // Skip business_name — auto-filled
                if (key !== 'business_name') {
                    ordered.push(key);
                }
            }
        }

        var $fields = $('#placeholderFields').empty();

        if (ordered.length === 0) {
            $('#placeholderSection').hide();
            return;
        }

        $('#placeholderSection').show();

        $.each(ordered, function (i, key) {
            var humanLabel = phLabels[key] || key.replace(/_/g, ' ');
            var isLong     = (key === 'message');
            var inputHtml  = isLong
                ? '<textarea rows="2" class="form-control zForm-control var-input" data-var="' + key + '" placeholder="' + humanLabel + '"></textarea>'
                : '<input type="text" class="form-control zForm-control var-input" data-var="' + key + '" placeholder="' + humanLabel + '">';

            // customer_name is already on the form — link it
            if (key === 'customer_name') {
                // Pre-fill from the already-visible #varCustomerName field
                var existingVal = $('#varCustomerName').val();
                // We don't add a duplicate field; the existing input handles it
                return; // skip
            }

            $fields.append(
                '<div class="col-12">' +
                    '<label class="zForm-label">' + humanLabel + '</label>' +
                    inputHtml +
                '</div>'
            );
        });

        // Live update preview when any placeholder field changes
        $fields.find('.var-input').on('input', renderPreview);
    }

    // ─── ⑤ Live Preview renderer ──────────────────────────────────────────────

    function renderPreview() {
        var values = collectValues();
        var body   = applyPlaceholders(rawBody,    values);
        var subj   = applyPlaceholders(rawSubject, values);

        $('#previewTo').text($('#sendToEmail').val() || '—');
        $('#previewSubject').text(subj || '—');
        $('#previewBody').text(body || labels.previewDefault);

        // Write final resolved body into the hidden textarea that gets submitted
        $('#sendBody').val(body);
    }

    function collectValues() {
        var vals = {
            business_name: labels.businessName,
            customer_name: $('#varCustomerName').val() || '',
        };
        // All other dynamic fields
        $('#placeholderFields .var-input').each(function () {
            vals[$(this).data('var')] = $(this).val() || '';
        });
        return vals;
    }

    function applyPlaceholders(text, values) {
        return text.replace(/\{(\w+)\}/g, function (match, key) {
            return (values[key] !== undefined && values[key] !== '') ? values[key] : match;
        });
    }

    // Update preview when To email changes
    $('#sendToEmail').on('input', renderPreview);

    // Update preview when the customer name field (step 1) changes
    $('#varCustomerName').on('input', renderPreview);

    // ─── ⑥ Send Email Form Submit ─────────────────────────────────────────────

    $('#sendEmailForm').on('submit', function (e) {
        e.preventDefault();

        // Ensure the resolved body is written to hidden textarea
        renderPreview();

        if (!$('#sendBody').val().trim()) {
            toastr.warning('Please select a template or write a message.');
            return;
        }

        var $btn = $('#sendEmailBtn');
        $btn.html('<i class="fa-solid fa-spinner fa-spin me-6"></i>' + labels.sending).prop('disabled', true);

        commonAjax('POST', sendRoute,
            function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    bootstrap.Modal.getInstance($('#sendEmailModal')[0]).hide();
                    $('#sendEmailForm')[0].reset();
                    $('#placeholderFields').empty();
                    $('#placeholderSection').hide();
                    rawBody = rawSubject = '';
                    renderPreview();
                } else {
                    toastr.error(res.message);
                }
                $btn.html('<i class="fa-solid fa-paper-plane me-6"></i>' + labels.sendEmail).prop('disabled', false);
            },
            function () {
                toastr.error(labels.serverError);
                $btn.html('<i class="fa-solid fa-paper-plane me-6"></i>' + labels.sendEmail).prop('disabled', false);
            },
            $(this).serialize()
        );
    });

    // Reset state when modal closes
    $('#sendEmailModal').on('hidden.bs.modal', function () {
        rawBody = rawSubject = '';
        $('#sendEmailForm')[0].reset();
        $('#placeholderFields').empty();
        $('#placeholderSection').hide();
        $('#previewBody').text(labels.previewDefault);
        $('#previewTo, #previewSubject').text('—');
        $('#sendBody').val('');
    });

})(jQuery);
