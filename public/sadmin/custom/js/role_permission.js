(function( $ ){
    ("use strict");

    let dataTable;
    $(document).on('input', '#rolePremissionListTableSearch', function () {
        dataTable.search($(this).val()).draw();
    });

    dataTable = $("#rolePremissionListTable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: false,
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
        ajax: $('#roleListRoute').val(),
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            { "data": "DT_RowIndex", "name": "DT_RowIndex", "orderable": false, "searchable": false },
            { "data": "role_name", "name": "role_name" },
            { "data": "status" },
            { "data": "action" }
        ]
    });


    $(document).on('click', '.radio-action', function () {
        if ($(this).val() == 1){
            $(".selected-badge").html('<span class="zBadge zBadge-active selected-badge">Active</span>');
        }else{
            $(".selected-badge").html(' <span class="zBadge zBadge-deactivate">Deactivate</span>');
        }
    });

})(jQuery);
