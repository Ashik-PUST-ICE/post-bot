(function ($) {
    "use strict";

    /** Bootstrap 5 has no jQuery .modal(); use native API (with jQuery fallback). */
    function showBsModal(selector) {
        var el = typeof selector === 'string' ? document.querySelector(selector) : selector;
        if (!el) {
            return;
        }
        if (el.jquery) {
            el = el[0];
        }
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(el).show();
        } else {
            $(el).modal('show');
        }
    }

    $(document).on('click', '#add', function () {
        var selector = $('#addModal');
        selector.find('.otherFields').html('');
        selector.find('input[name=sync_stripe]').prop('checked', false);
        showBsModal('#addModal');
    });

    $(document).on('click', '.edit-package', function () {
        var infoUrl = $('#packageInfoRoute').val();
        var pkgId = $(this).data('id');
        if (!infoUrl || pkgId == null || pkgId === '') {
            toastr.error('Missing package info URL or id');
            return;
        }
        commonAjax('GET', infoUrl, getDataEditRes, getDataEditRes, { id: pkgId });
    });

    function getDataEditRes(response) {
        // Error callback receives jqXHR; success receives { status, data, message }
        if (response && typeof response.getResponseHeader === 'function') {
            commonHandler(response);
            return;
        }
        if (!response || response.status === false || !response.data) {
            if (response && response.message) {
                toastr.error(response.message);
            }
            return;
        }

        var selector = $('#editModal');
        if (!selector.length || !document.getElementById('editModal')) {
            toastr.error('Edit modal not found');
            return;
        }

        try {
        selector.find('.is-invalid').removeClass('is-invalid');
        selector.find('.error-message').remove();

        selector.find('input[name=id]').val(response.data.id);
        // Do not set .val() on file inputs (browser security — can throw and block the modal).
        selector.find('input[name=name]').val(response.data.name);
        selector.find('input[name=page_limit]').val(response.data.page_limit);
        selector.find('input[name=message_limit]').val(response.data.message_limit);

        // others — API may send array (Package cast) or legacy JSON string
        var otherHtmlFields = '';
        var otherRaw = response.data.others;
        var otherFields = [];
        if (Array.isArray(otherRaw)) {
            otherFields = otherRaw;
        } else if (typeof otherRaw === 'string' && otherRaw.trim() !== '') {
            try {
                otherFields = JSON.parse(otherRaw);
            } catch (e) {
                otherFields = [];
            }
        }
        if (otherFields && otherFields.length) {
            otherFields.forEach((val) => {
                otherHtmlFields += otherFiledTemplate(val);
            });
        }
        selector.find('.icon-preview').attr('src', response.data.icon_url);
        selector.find('.otherFields').html(otherHtmlFields);

        selector.find('input[name=monthly_price]').val(response.data.monthly_price)
        selector.find('input[name=yearly_price]').val(response.data.yearly_price)
        if (response.data.status == 1) {
            selector.find('input[name=status]').prop('checked', true);
        } else {
            selector.find('input[name=status]').prop('checked', false);
        }
        if (response.data.is_trail == 1) {
            selector.find('input[name=is_trail]').prop('checked', true);
        } else {
            selector.find('input[name=is_trail]').prop('checked', false);
        }
        if (response.data.is_default == 1) {
            selector.find('input[name=is_default]').prop('checked', true);
        } else {
            selector.find('input[name=is_default]').prop('checked', false);
        }

        // Stripe — reset toggle, then show info panel if already synced
        selector.find('input[name=sync_stripe]').prop('checked', false);
        var stripePanel = selector.find('.stripe-ids-panel');
        if (response.data.stripe_product_id) {
            stripePanel.show();
            stripePanel.find('.stripe-product-id').text(response.data.stripe_product_id);
            stripePanel.find('.stripe-monthly-id').text(response.data.stripe_monthly_plan_id || '—');
            stripePanel.find('.stripe-yearly-id').text(response.data.stripe_yearly_plan_id || '—');
        } else {
            stripePanel.hide();
            stripePanel.find('.stripe-product-id').text('—');
            stripePanel.find('.stripe-monthly-id').text('—');
            stripePanel.find('.stripe-yearly-id').text('—');
        }

        showBsModal('#editModal');
        } catch (err) {
            console.error(err);
            toastr.error(err.message || 'Could not load package into the form');
        }
    }

    $('.addOtherField').on('click', function () {
        var selector = $(this).closest('.modal')
        selector.find('.otherFields').append(otherFiledTemplate());
    });

    $(document).on('click', '.removeOtherField', function () {
        $(this).parent().remove();
    });

    function otherFiledTemplate(val = null) {
        return `<div class="input-group mb-3 flex-nowrap mt-3">
                    <input type="text" name="others[]" class="form-control zForm-control" value="${val ?? ''}">
                    <button type="button"
                        class="bg-danger input-group-text text-white removeOtherField"
                        id="basic-addon1"><i class="fa-solid fa-trash"></i></button>
                </div>`;
    }

    var dataTable;

    $(document).on('input', '#searchByPackage', function () {
            dataTable.search($(this).val()).draw();
    });

    dataTable = $("#packageDataTable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        responsive: true,
        searching: true,
        ajax: $('#packageIndexRoute').val(),
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
            searchPlaceholder: "Search event",
            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            { data: 'DT_RowIndex', "name": 'DT_RowIndex', orderable: false, searchable: false, },
            { data: "name", name: "packages.name" },
            { data: "icon", name: "packages.icon" },
            { data: "monthly_price", name: "packages.monthly_price" },
            { data: "yearly_price", name: "packages.yearly_price" },
            { data: "status", name: "status" },
            { data: "trail", name: "trail" },
            { data: "action", name: "action" },
        ],
    });

    $('#assignPackage').on('click', function () {
        var selector = $('#assignPackageModal');
        selector.find('.is-invalid').removeClass('is-invalid');
        selector.find('.error-message').remove();
        selector.find('form').trigger('reset');
        showBsModal('#assignPackageModal');
    })


    var packageUserDataTable;

    $(document).on('input', '#searchByUserPackage', function () {
            packageUserDataTable.search($(this).val()).draw();
    });

    packageUserDataTable = $("#packageUserDataTableList").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        searching: true,
        responsive: {
            breakpoints: [
                { name: "desktop", width: Infinity },
                { name: "tablet", width: 1400 },
                { name: "fablet", width: 768 },
                { name: "phone", width: 480 },
            ],
        },
        ajax: $('#packagesUserRoute').val(),
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
            searchPlaceholder: "Search event",
            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            { data: "user_name", name: "users.name" },
            { data: "package_name", name: "packages.name" },
            { data: "start_date", name: "user_packages.start_date" },
            { data: "end_date", name: "user_packages.end_date" },
            { data: "payment_status", name: "subscription_orders.payment_status" },
            { data: "status", name: "user_packages.status" },
            { data: "action", name: "action" }
        ],
    });

    $(document).on('click', '.edit-user-package', function () {
        var id = $(this).data('id');
        var infoRoute = $('#userPackageInfoRoute').val();
        var baseUpdate = $('#userPackageUpdateBaseUrl').val();
        if (!infoRoute || !baseUpdate) {
            return;
        }
        commonAjax('GET', infoRoute, function (response) {
            if (!response.status) {
                toastr.error(response.message || 'Error');
                return;
            }
            var d = response.data;
            $('#editUserPackageUser').text(d.user_name + (d.user_email ? ' (' + d.user_email + ')' : ''));
            $('#editUserPackagePkg').text(d.package_name);
            $('#editUserPackageStart').val(d.start_date);
            $('#editUserPackageEnd').val(d.end_date);
            $('#editUserPackageStatus').val(String(d.status));
            if (typeof $.fn.niceSelect !== 'undefined' && $('#editUserPackageStatus').next('.nice-select').length) {
                $('#editUserPackageStatus').niceSelect('update');
            }
            $('#editUserPackageForm').attr('action', baseUpdate.replace(/\/$/, '') + '/' + id);
            showBsModal('#editUserPackageModal');
        }, commonHandler, { id: id });
    });

})(jQuery);
