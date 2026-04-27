(function ($) {
    "use strict";

    $(document).on('click', '#chooseAPlan', function () {
        commonAjax('GET', $('#chooseAPlanRoute').val(), function (response) {
            $('#planListBlock').html(response.data);
            $('#choosePackageModal').modal('show');
        }, commonHandler);
    });

    $(document).on('click', '.selectPackage', function () {
        var package_id = $(this).data('id');
        var duration_type = $('.package-type-yearly-monthly').val();
        $('#package_id').val(package_id);
        $('#duration_type').val(duration_type);
        
        commonAjax('GET', $('#chooseAPlanRoute').val(), function (response) {
            $('#choosePackageModal').modal('hide');
            $('#gatewayListBlock').html(response.data);
            $('#paymentMethodModal').modal('show');
        }, commonHandler);
    });

    $(document).on('click', '.subscriptionCancel', function (e) {
        var form = $(this).closest('form');
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to cancel your subscription!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    window.setPaymentModal = function (response) {
        if (response.success) {
            $('#choosePackageModal').modal('hide');
            $('#gatewayListBlock').html(response.data);
            $('#paymentMethodModal').modal('show');
        } else {
            commonHandler(response);
        }
    };

})(jQuery);
