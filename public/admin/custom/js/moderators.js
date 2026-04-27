(function( $ ){
    ("use strict");

    let dataTable;
    $(document).on('input', '#moderatorTableSearch', function () {
        dataTable.search($(this).val()).draw();
    });

    dataTable = $("#moderatorTable").DataTable({
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
        ajax: $('#moderatorTableRoute').val(),
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            { "data": "DT_RowIndex", "name": "DT_RowIndex", "orderable": false, "searchable": false },
            { "data": "name", "name": "name" },
            { "data": "email", "name": "email" },
            { "data": "roles", "name": "roles" },
            { "data": "status", "name": "status" },
            { "data": "action", "name": "action" }
        ]
    });

    window.getEditModal = function (url, modalId) {
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                $(modalId).find('.modal-content').html(data);
                $(modalId).modal('show');
            }
        });
    }

    window.deleteItem = function (url, tableId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.status) {
                            toastr.success(data.message);
                            $('#' + tableId).DataTable().ajax.reload();
                        } else {
                            toastr.error(data.message);
                        }
                    }
                });
            }
        })
    }

})(jQuery);
