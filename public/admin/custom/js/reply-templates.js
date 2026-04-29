(function ($) {
    "use strict";

    var getDataRoute = $('#rtGetDataRoute').val();
    var getInfoRoute = $('#rtGetInfoRoute').val();
    var labelAdd     = $('#rtLabelAdd').val()  || 'Add Template';
    var labelEdit    = $('#rtLabelEdit').val() || 'Edit Template';

    // ── DataTable ──────────────────────────────────────────────────────────────
    var dt = $('#replyTemplateTable').DataTable({
        pageLength: 15,
        ordering: false,
        serverSide: true,
        processing: true,
        searching: true,
        responsive: true,
        ajax: getDataRoute,
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next:     "<i class='fa-solid fa-angles-right'></i>",
            },
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            { data: 'DT_RowIndex',   orderable: false, searchable: false },
            { data: 'title',         name: 'title' },
            { data: 'preview',       name: 'content' },
            { data: 'platform_badge',name: 'platform' },
            { data: 'usage_count',   name: 'usage_count' },
            { data: 'status_badge',  name: 'status' },
            { data: 'action',        name: 'action', orderable: false },
        ],
    });

    // ── Open Add modal ─────────────────────────────────────────────────────────
    $('#btnAddTemplate').on('click', function () {
        $('#modalTitle').text(labelAdd);
        $('#templateId').val('');
        $('#templateTitle').val('');
        $('#templateContent').val('');
        $('#templatePlatform').val('all');
        $('#templateStatus').prop('checked', true);
        $('#templateModal').modal('show');
    });

    // ── Open Edit modal ────────────────────────────────────────────────────────
    $(document).on('click', '.edit-template', function () {
        var id = $(this).data('id');
        commonAjax('GET', getInfoRoute, function (res) {
            $('#modalTitle').text(labelEdit);
            $('#templateId').val(res.data.id);
            $('#templateTitle').val(res.data.title);
            $('#templateContent').val(res.data.content);
            $('#templatePlatform').val(res.data.platform);
            $('#templateStatus').prop('checked', res.data.status == 1);
            $('#templateModal').modal('show');
        }, function () {}, { id: id });
    });

    // ── Delete ─────────────────────────────────────────────────────────────────
    $(document).on('click', '.delete-template', function () {
        deleteItem($(this).data('route'), 'replyTemplateTable');
    });

})(jQuery);
