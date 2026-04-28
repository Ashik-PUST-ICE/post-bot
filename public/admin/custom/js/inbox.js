(function ($) {
    "use strict";

    // ── Status filter tabs ─────────────────────────────────────────────────────
    $(document).on('click', '.inbox-tab', function (e) {
        e.preventDefault();
        var status = $(this).data('status');

        // Update active tab style
        $('.inbox-tab').removeClass('bg-main-color text-white bd-c-main-color')
                       .addClass('bg-white text-textBlack bd-c-stroke');
        $(this).removeClass('bg-white text-textBlack bd-c-stroke')
               .addClass('bg-main-color text-white bd-c-main-color');

        // Update hidden input and reload table
        $('#inboxStatusFilter').val(status);
        inboxTable.ajax.reload();
    });

    // ── DataTable ──────────────────────────────────────────────────────────────
    var inboxTable = $("#inboxTable").DataTable({
        pageLength: 15,
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
            searchPlaceholder: "Search by contact name...",
            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
        },
        ajax: {
            url: $('#inboxTableRoute').val(),
            data: function (d) {
                d.status   = $('#inboxStatusFilter').val();
                d.platform = $('#inboxPlatformFilter').val();
            }
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            { "data": "platform",      "name": "platform",      "orderable": false },
            { "data": "contact",       "name": "contact_name" },
            { "data": "last_message",  "name": "last_message",  "orderable": false },
            { "data": "status_badge",  "name": "status",        "orderable": false },
            { "data": "ai_replied",    "name": "ai_replied",    "orderable": false },
            { "data": "last_at",       "name": "last_message_at" },
            { "data": "action",        "name": "action",        "orderable": false, "searchable": false },
        ]
    });

    // ── Platform filter change → reload table ──────────────────────────────────
    $(document).on('change', '#inboxPlatformFilter', function () {
        inboxTable.ajax.reload();
    });

    // ── Live search (debounced) ────────────────────────────────────────────────
    var searchTimer;
    $(document).on('input', '#inboxSearch', function () {
        clearTimeout(searchTimer);
        var q = $(this).val();
        searchTimer = setTimeout(function () {
            inboxTable.search(q).draw();
        }, 400);
    });

})(jQuery);
