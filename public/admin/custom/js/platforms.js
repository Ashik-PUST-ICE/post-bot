(function ($) {
    "use strict";

    var platformTable;

    // ── DataTable ──────────────────────────────────────────────────────────────
    platformTable = $("#platformTable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        responsive: true,
        searching: true,
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
            searchPlaceholder: "Search here...",
            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
        },
        ajax: $('#platformTableRoute').val(),
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            { "data": "DT_RowIndex",   "name": "DT_RowIndex", "orderable": false, "searchable": false },
            { "data": "platform_type", "name": "platform_type" },
            { "data": "platform_name", "name": "platform_name" },
            { "data": "platform_id",   "name": "platform_id" },
            { "data": "auto_reply",    "name": "auto_reply",  "orderable": false },
            { "data": "status",        "name": "status" },
            { "data": "action",        "name": "action",      "orderable": false, "searchable": false },
        ]
    });

    // ── Search ─────────────────────────────────────────────────────────────────
    $(document).on('input', '#platformSearch', function () {
        platformTable.search($(this).val()).draw();
    });

    // ── Auto-reply toggle ──────────────────────────────────────────────────────
    $(document).on('change', '.platform-auto-reply-toggle', function () {
        var url = $(this).data('route');
        var $toggle = $(this);
        $.ajax({
            type: 'POST',
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message);
                    $toggle.prop('checked', !$toggle.prop('checked'));
                }
            },
            error: function (err) {
                commonHandler(err);
                $toggle.prop('checked', !$toggle.prop('checked'));
            }
        });
    });

    // ── Edit: load info via AJAX → fill modal ──────────────────────────────────
    $(document).on('click', '.edit-platform-btn', function () {
        var id         = $(this).data('id');
        var infoRoute  = $('#platformInfoRoute').val();

        commonAjax('GET', infoRoute, function (res) {
            if (!res.status) { toastr.error(res.message); return; }
            var d = res.data;

            var modal = $('#editPlatformModal');
            modal.find('#edit_platform_id').val(d.id);
            modal.find('#edit_platform_id_input').val(d.platform_id || '');
            modal.find('#edit_platform_name').val(d.platform_name);
            modal.find('#edit_phone_number').val(d.phone_number || '');
            modal.find('#edit_access_token').val(d.access_token || '');  // prefill token as requested
            modal.find('#edit_status').val(d.status);
            modal.find('#editPlatformForm').attr(
                'action',
                $('#platformUpdateRoute').val().replace(':id', d.id)
            );
            modal.modal('show');
        }, commonHandler, { id: id });
    });

    // ── Re-subscribe Webhook ───────────────────────────────────────────────────
    $(document).on('click', '.resubscribe-platform-btn', function () {
        var url = $(this).data('route');
        Swal.fire({
            title: 'Re-subscribe Webhook?',
            text: "This will re-register this platform with Meta so messages are delivered to your inbox.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6366f1',
            cancelButtonColor:  '#6b7280',
            confirmButtonText:  'Yes, re-subscribe!',
            cancelButtonText:   'Cancel'
        }).then(function (result) {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Subscribing...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
                $.ajax({
                    type: 'POST',
                    url: url,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (res) {
                        Swal.close();
                        if (res.status) {
                            toastr.success(res.message);
                            platformTable.ajax.reload(null, false);
                        } else {
                            toastr.error(res.message);
                        }
                    },
                    error: function (err) {
                        Swal.close();
                        var msg = err.responseJSON ? (err.responseJSON.message || 'Server error') : 'Server error';
                        toastr.error(msg);
                    }
                });
            }
        });
    });

    // ── Delete ─────────────────────────────────────────────────────────────────
    $(document).on('click', '.delete-platform-btn', function () {
        var url = $(this).data('route');
        Swal.fire({
            title: 'Are you sure?',
            text: "This platform connection will be permanently removed.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor:  '#6b7280',
            confirmButtonText:  'Yes, delete it!'
        }).then(function (result) {
            if (result.isConfirmed) {
                commonAjax('POST', url, function (res) {
                    if (res.status) {
                        toastr.success(res.message);
                        location.reload();
                    } else {
                        toastr.error(res.message);
                    }
                }, commonHandler, { _token: $('meta[name="csrf-token"]').attr('content') });
            }
        });
    });

    // ── Platform type change → show/hide WhatsApp fields ─────────────────────
    $(document).on('change', '#platformTypeSelect', function () {
        var WA_TYPE = parseInt($('#whatsappTypeValue').val(), 10);
        if (parseInt($(this).val(), 10) === WA_TYPE) {
            $('.whatsapp-field').show();
        } else {
            $('.whatsapp-field').hide();
        }
    });

    // ── After add/edit success → reload table & close modal ──────────────────
    $(document).on('ajaxFormSuccess', function (e, res) {
        if (res.status) {
            location.reload();
        }
    });

    // ── Token visibility toggle (Eye button) ───────────────────────────────────
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

})(jQuery);
