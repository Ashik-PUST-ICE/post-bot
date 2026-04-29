(function ($) {
    "use strict";

    // ── Open "Choose a Plan" modal ─────────────────────────────────────────────
    $(document).on('click', '#chooseAPlan', function () {
        commonAjax('GET', $('#chooseAPlanRoute').val(), function (response) {
            $('#planListBlock').html(response.data);
            syncDurationType();
            $('#choosePackageModal').modal('show');
        }, commonHandler);
    });

    // ── Monthly / Yearly tab toggle inside the plan modal ─────────────────────
    $(document).on('click', '#billingMonthly-tab', function () {
        $('#planListBlock').find('.zPrice-plan-yearly').addClass('d-none');
        $('#planListBlock').find('.zPrice-plan-monthly').removeClass('d-none');
        $('#planListBlock').find('input[name="duration_type"]').val(1);
    });

    $(document).on('click', '#billingYearly-tab', function () {
        $('#planListBlock').find('.zPrice-plan-monthly').addClass('d-none');
        $('#planListBlock').find('.zPrice-plan-yearly').removeClass('d-none');
        $('#planListBlock').find('input[name="duration_type"]').val(2);
    });

    function syncDurationType() {
        var isYearly = $('#billingYearly-tab').hasClass('active');
        $('#planListBlock').find('input[name="duration_type"]').val(isYearly ? 2 : 1);
    }

    // ── Called by form.ajax after getGateway POST (data-handler="setPaymentModal")
    window.setPaymentModal = function (response) {
        if (response.status) {
            $('#choosePackageModal').modal('hide');
            $('#gatewayListBlock').html(response.data);
            // Reset previous selections
            $('#selectGateway').val('');
            $('#selectCurrency').val('');
            $('#gatewayCurrencyAmount').text('');
            $('#currencyAppend').html('');
            $('#bankSection').addClass('d-none');
            $('#paymentMethodModal').modal('show');
        } else {
            // response is plain JSON {success:false, message:"..."} — use toastr directly
            toastr.error(response.message || 'Something went wrong. Please try again.');
        }
    };

    // ── Gateway card clicked — set gateway, load currencies ───────────────────
    $(document).on('click', '.paymentGateway', function () {
        var $btn        = $(this);
        var gatewaySlug = $btn.data('gateway');
        var gatewayId   = $btn.data('id');
        var packageId   = $btn.data('package_id');
        var durationType = $btn.data('duration_type');

        // Highlight selected gateway card
        $('.payment-item').removeClass('bd-c-main-color').addClass('bd-c-stroke');
        $('.paymentGateway').text('Select');
        $btn.closest('.payment-item').removeClass('bd-c-stroke').addClass('bd-c-main-color');
        $btn.text('Selected ✓');

        // Set hidden form fields
        $('#selectGateway').val(gatewaySlug);
        $('#package_id').val(packageId);
        $('#duration_type').val(durationType);

        // Bank section toggle
        if (gatewaySlug === 'bank') {
            $('#bankSection').removeClass('d-none');
        } else {
            $('#bankSection').addClass('d-none');
        }

        // Load currencies for selected gateway
        $('#currencyAppend').html('<li class="text-para-text fs-14">Loading...</li>');
        commonAjax('GET', $('#getCurrencyByGatewayRoute').val(), function (response) {
            var currencies = response.data;
            var html = '';
            if (currencies && currencies.length > 0) {
                var planAmount = parseFloat($('#planAmount').val()) || 0;
                $.each(currencies, function (i, c) {
                    var converted  = (planAmount * parseFloat(c.conversion_rate)).toFixed(2);
                    var priceLabel = gatewayCurrencyPrice(converted, c.currency); // use code, not symbol
                    html += '<li class="d-flex justify-content-between align-items-center">' +
                        '<label class="d-flex align-items-center g-10 cursor-pointer">' +
                        '<input type="radio" name="currency_radio" value="' + c.currency + '"' +
                        ' data-code="' + c.currency + '" data-amount="' + converted + '"' +
                        (i === 0 ? ' checked' : '') + '>' +
                        ' <span class="fs-14 fw-400 lh-16 text-title-black">' + c.currency + '</span>' +
                        '</label>' +
                        '<span class="fs-14 fw-600 lh-16 text-title-black">' + priceLabel + '</span>' +
                        '</li>';
                    // Auto-select first currency
                    if (i === 0) {
                        $('#selectCurrency').val(c.currency);
                        $('#gatewayCurrencyAmount').text('(' + priceLabel + ')');
                    }
                });
            } else {
                html = '<li><p class="text-danger fs-14">No currency configured for this gateway.</p></li>';
            }
            $('#currencyAppend').html(html);
        }, commonHandler, { id: gatewayId });
    });

    // ── Currency radio change ─────────────────────────────────────────────────
    $(document).on('change', 'input[name="currency_radio"]', function () {
        $('#selectCurrency').val($(this).val());
        var priceLabel = gatewayCurrencyPrice($(this).data('amount'), $(this).data('code'));
        $('#gatewayCurrencyAmount').text('(' + priceLabel + ')');
    });

    // ── Bank dropdown — show details ──────────────────────────────────────────
    $(document).on('change', '#bank_id', function () {
        var details = $('option:selected', this).data('details') || '';
        $('#bankDetails').find('p').html(details);
    });

    // ── Pay Now button — validate and submit form ─────────────────────────────
    $(document).on('click', '#payBtn', function () {
        if (!$('#selectGateway').val()) {
            toastr.error('Please select a payment gateway.');
            return;
        }
        if (!$('#selectCurrency').val()) {
            toastr.error('Please select a currency.');
            return;
        }
        $(this).closest('form').submit();
    });

    // ── Cancel subscription confirmation ─────────────────────────────────────
    $(document).on('click', '.subscriptionCancel', function () {
        var form = $(this).closest('form');
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to cancel your subscription!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel it!'
        }).then(function (result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // ── Auto-trigger gateway modal when redirected from registration (?id=X) ─
    $(document).ready(function () {
        var planId = $('#requestPlanId').val();
        var gatewayRoute = $('#getGatewayRoute').val();
        if (planId && gatewayRoute) {
            var formData = new FormData();
            formData.append('id', planId);
            formData.append('duration_type', 1);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            commonAjax('POST', gatewayRoute, window.setPaymentModal, commonHandler, formData);
        }
    });

})(jQuery);
