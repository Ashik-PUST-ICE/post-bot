(function ($) {
    "use strict";

    // Routes & labels come from hidden inputs in templates.blade.php
    var getRoute    = $('#getTemplateRoute').val();
    var updateRoute = $('#updateTemplateRoute').val();
    var sendRoute   = $('#sendMailRoute').val();
    var labels      = {
        saving:          $('#lblSaving').val(),
        saveTemplate:    $('#lblSaveTemplate').val(),
        sending:         $('#lblSending').val(),
        sendEmail:       $('#lblSendEmail').val(),
        serverError:     $('#lblServerError').val(),
        loadFailed:      $('#lblLoadFailed').val(),
    };

    // ── Open Edit Modal ────────────────────────────────────────────────────────
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

    // ── Save Template ──────────────────────────────────────────────────────────
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
            $('#editTemplateForm').serialize()
        );
    });

    // ── Open Send Modal (from template card Send button) ──────────────────────
    $(document).on('click', '.sendTemplateBtn', function () {
        $('#sendSubject').val($(this).data('subject'));
        $('#sendBody').val($(this).data('body'));
        new bootstrap.Modal($('#sendEmailModal')[0]).show();
    });

    // ── Auto-fill subject + body from dropdown ─────────────────────────────────
    $('#useTemplateSelect').on('change', function () {
        var $opt = $(this).find(':selected');
        if ($opt.val()) {
            $('#sendSubject').val($opt.data('subject'));
            $('#sendBody').val($opt.data('body'));
        }
    });

    // ── Send Email to Customer ─────────────────────────────────────────────────
    $('#sendEmailForm').on('submit', function (e) {
        e.preventDefault();
        var $btn = $('#sendEmailBtn');
        $btn.html('<i class="fa-solid fa-spinner fa-spin me-6"></i>' + labels.sending).prop('disabled', true);

        commonAjax('POST', sendRoute,
            function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    bootstrap.Modal.getInstance($('#sendEmailModal')[0]).hide();
                    $('#sendEmailForm')[0].reset();
                } else {
                    toastr.error(res.message);
                }
                $btn.html('<i class="fa-solid fa-paper-plane me-6"></i>' + labels.sendEmail).prop('disabled', false);
            },
            function () {
                toastr.error(labels.serverError);
                $btn.html('<i class="fa-solid fa-paper-plane me-6"></i>' + labels.sendEmail).prop('disabled', false);
            },
            $('#sendEmailForm').serialize()
        );
    });

})(jQuery);
