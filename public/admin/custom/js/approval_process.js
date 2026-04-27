(function( $ ){
    ("use strict");
    var dataTable;

    $(document).on('change', '#searchBySessionType', function () {
            dataTable.search($(this).val()).draw();
    });


    $(document).on('input', '#appraisementListTableSearch', function () {
        dataTable.search($(this).val()).draw();
    });

    dataTable = $("#processApprovalListTable").DataTable({
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
                searchPlaceholder: "Search event",
                search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
            },
            ajax: $('#processApprovalListRoute').val(),
            dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
            columns: [
                { "data": "session_name", "name": "kpi_sessions.kpi_phase_name" },
                { "data": "session_type", "name": "kpi_session_types.name" },
                { "data": "userName", "name": "users.name" },
                { "data": "user_email", "name": "users.email"},
                { "data": "status" },
                { "data": "action" }
            ],
            'columnDefs' : [
                { 'visible': false, 'targets': [1] }
            ]
        });

})(jQuery);
