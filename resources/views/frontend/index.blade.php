@extends('frontend.layouts.app')
@push('title')
{{ __(@$pageTitle) }}
@endpush
@section('content')


@if (isset($section['hero_area']) && $section['hero_area']->status == STATUS_ACTIVE)
<!-- Start Banner -->
<section class="landing-banner-wrap position-relative z-1">
    <div class="container">
        <div class="landing-hero-content">
            <h4 class="title">{{ __($section['hero_area']->title) }}</h4>
            <p class="info">{{ __($section['hero_area']->description) }}</p>
            <a href="{{ route('login') }}" class="btnLink">{{ __('Request a Demo') }}</a>
        </div>
        <!--  -->
        <div class="landing-hero-img">
            <div class="img"><img src="{{ getFileUrl($section['hero_area']->banner_image) }}"
                    alt="{{ __(getOption('app_name')) }}" /></div>
        </div>
    </div>
</section>
<!-- End Banner -->
@endif

@if (isset($section['features']) && $section['features']->status == STATUS_ACTIVE)
<!-- Start Features List -->
<section class="py-sm-150 py-30 landing-feature-wrap" id="features">
    <div class="container">
        <!--  -->
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center pb-55">
                    <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">
                        {{ __($section['features']->page_title) }}</p>
                    <h4 class="lh-sm-57 lh-44 landing-section-title text-white">
                        <span class="d-sm-block">{{ __($section['features']->title) }}</span>
                    </h4>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="row rg-30 justify-content-center">
            @foreach ($features as $feature)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="features-list-item">
                    <div class="icon"><img src="{{ getFileUrl($feature->image) }}"
                            alt="{{ __(getOption('app_name')) }}" />
                    </div>
                    <div class="title"><span class="d-md-block">{{ __($feature->title) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- End Features List -->
@endif

@if (isset($section['services']) && $section['services']->status == STATUS_ACTIVE)
<!-- Start Features Block -->
<section class="bg-white py-sm-150 py-30 position-relative z-1" id="goal-setup">
    <div class="container">
        <div class="features-block">
            @foreach ($services as $service)
            <div class="features-block-item">
                <div class="row align-items-center rg-20">
                    <div class="col-lg-6">
                        <div class="item-content">
                            <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">
                                {{ __($service->name) }}</p>
                            <h4 class="landing-section-title text-ld-black">{{ __($service->title) }}</h4>
                            <p class="fs-18 fw-600 lh-26 text-para-text max-w-400 pb-20">{{ __($service->sub_title) }}
                            </p>
                            <ul class="lists">
                                @foreach ($service->others as $other)
                                <li>
                                    <div class="icon"><img
                                            src="{{ asset('assets/images/icon/features-check-icon.svg') }}" alt="" />
                                    </div>
                                    <div class="content">
                                        <p class="info">{{ __($other) }}</p>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="image"><img src="{{ getFileUrl($service->image) }}"
                                alt="{{ __(getOption('app_name')) }}" /></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- End Features Block -->
@endif


@if (isset($section['core_features']) && $section['core_features']->status == STATUS_ACTIVE)
<!-- Start Core Features -->
<section class="py-sm-150 py-30 landing-coreFeature-wrap position-relative z-2" id="core-features"
    data-background="assets/images/ld-bg-dot.svg">
    <div class="container">
        <!--  -->
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center pb-55">
                    <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">
                        {{ __($section['core_features']->page_title) }}</p>
                    <h4 class="lh-sm-57 lh-44 landing-section-title text-white">
                        {{ __($section['core_features']->title) }}
                    </h4>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="d-flex align-items-start flex-column flex-lg-row g-20">
            <div class="nav flex-row flex-lg-column flex-wrap flex-lg-nowrap justify-content-center nav-pills zTab-reset zTab-vertical-one g-20"
                id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @foreach ($coreFeatures as $key => $coreFeature)
                <button class="nav-link {{ $key == 0 ? 'active' : '' }}" id="v-pills-Dashboard-tab-{{ $key }}"
                    data-bs-toggle="pill" data-bs-target="#v-pills-Dashboard-{{ $key }}" type="button" role="tab"
                    aria-controls="v-pills-Dashboard-{{ $key }}" aria-selected="true">{{$key+1}}. {{
                    __($coreFeature->title)
                    }}</button>
                @endforeach
            </div>
            <div class="tab-content" id="v-pills-tabContent">
                @foreach ($coreFeatures as $key => $coreFeature)
                <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="v-pills-Dashboard-{{ $key }}"
                    role="tabpanel" aria-labelledby="v-pills-Dashboard-tab-{{ $key }}" tabindex="0">
                    <div class="landing-coreFeatures-tabContent">
                        <div class="img">
                            <img src="{{ getFileUrl($coreFeature->image) }}" alt="{{ __($coreFeature->title) }}" />
                        </div>
                        <div class="content">
                            <h4 class="title">{{ __($coreFeature->title) }}</h4>
                            <p class="info">{{ __($coreFeature->description) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- End Core Features -->
@endif

@if (isset($section['choose_us']) && $section['choose_us']->status == STATUS_ACTIVE)
<!-- Start Why Choose Us -->
<section class="py-sm-150 py-30 landing-whyChooseUs-section"
    data-background="{{asset('assets/images/ld-why-choose-us.png')}}" id="why-us">
    <div class="container">
        <!--  -->
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center pb-55">
                    <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">
                        {{ __($section['choose_us']->page_title) }}</p>
                    <h4 class="landing-section-title text-ld-black">{{ __($section['choose_us']->title) }}</h4>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="row rg-20 justify-content-center">
            @foreach ($chooseUs as $choose)
            <div class="col-lg-4 col-sm-6">
                <div class="landing-whyChooseUs-item">
                    <div class="icon">
                        <img src="{{ getFileUrl($choose->image) }}" alt="img" />
                    </div>
                    <div class="title-wrap">
                        <h4 class="title"><span class="d-md-block">{{ __($choose->title) }}</span></h4>
                    </div>
                    <p class="info">{{ __($choose->description) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- End Why Choose Us -->
@endif

@if (isset($section['pricing']) && $section['pricing']->status == STATUS_ACTIVE)
<!-- Start Pricing -->
<section class="py-sm-150 py-30 landing-pricing-wrap position-relative z-1 bg-white" id="price">
    <div class="container">
        <!--  -->
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="text-center pb-25">
                    <p class="landing-section-subtitle bd-one bd-c-main-color">{{ __($section['pricing']->page_title) }}
                    </p>
                    <h4 class="lh-sm-57 lh-44 landing-section-title text-ld-black">{{ __($section['pricing']->title) }}
                    </h4>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="d-flex justify-content-center align-items-center g-20 pb-55">
            <ul class="nav nav-tabs flex-column flex-sm-row zTab-reset zTab-four" id="pricePlanTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="billingMonthly-tab" data-bs-toggle="tab"
                        data-bs-target="#billingMonthly-tab-pane" type="button" role="tab"
                        aria-controls="billingMonthly-tab-pane" aria-selected="true">{{__('Monthly')}}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="billingYearly-tab" data-bs-toggle="tab"
                        data-bs-target="#billingYearly-tab-pane" type="button" role="tab"
                        aria-controls="billingYearly-tab-pane" aria-selected="false"
                        tabindex="-1">{{__('Yearly')}}</button>
                </li>
            </ul>
        </div>
@push('style')
<style>
    /* Premium Claude-like pricing override */
    .price-plan-one {
        background: #FFFFFF !important;
        border: 1px solid #E5E5E0 !important;
        border-radius: 20px !important;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.02) !important;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
        overflow: hidden;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .price-plan-one:hover {
        transform: translateY(-8px) !important;
        border-color: #D1D1C7 !important;
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.08), 0 1px 5px rgba(0, 0, 0, 0.03) !important;
    }

    /* Featured card highlight (sonnet/pro) */
    .price-plan-one.price-plan-standard {
        border: 2px solid #D97706 !important; /* Premium warm amber accent */
        box-shadow: 0 10px 35px -5px rgba(217, 119, 6, 0.08) !important;
    }
    
    .price-plan-one.price-plan-standard:hover {
        border-color: #B45309 !important;
        box-shadow: 0 25px 45px -10px rgba(217, 119, 6, 0.15) !important;
    }

    /* Elegant warm ivory background on hover */
    .price-plan-one .price-head {
        background: transparent !important;
        padding: 3rem 2rem 2rem !important;
        border-bottom: 1px solid #F0EFEA !important;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .price-plan-one .price-head::before, 
    .price-plan-one .price-head::after {
        display: none !important; /* Hide background shapes/vectors */
    }

    /* Claude-style squircle icon frame */
    .plan-icon-container {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background: #F5F4F0;
        border: 1px solid #E5E5E0;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .price-plan-one:hover .plan-icon-container {
        background: #EBEAE4;
        transform: scale(1.05);
    }

    /* Clean monochrome/colored symbols */
    .plan-icon-container img {
        width: 32px;
        height: 32px;
        object-fit: contain;
    }

    /* Typography fixes */
    .price-plan-one .price-head .title {
        font-family: "Inter Tight", "Nunito", sans-serif;
        font-size: 1.5rem !important;
        font-weight: 700 !important;
        letter-spacing: -0.02em;
        color: #1A1A17 !important;
        margin-bottom: 0.5rem !important;
        padding-bottom: 0 !important;
    }

    .price-plan-one .price-head .plan-price {
        font-size: 3rem !important;
        font-weight: 800 !important;
        letter-spacing: -0.04em;
        color: #1A1A17 !important;
        margin-top: 0.5rem !important;
    }

    /* Body improvements */
    .price-plan-one .price-body {
        padding: 2.25rem 2rem 2.25rem !important;
        background: #FCFCFB;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .price-plan-one .price-body ul {
        border-bottom: none !important;
        padding: 0 !important;
        margin-bottom: 2.25rem !important;
    }

    .price-plan-one .price-body ul li {
        margin-bottom: 0.875rem !important;
    }

    .price-plan-one .price-body ul li p {
        font-size: 1rem !important;
        font-weight: 500 !important;
        color: #4A4A45 !important;
        line-height: 1.5 !important;
    }

    /* Claude-style elegant buttons */
    .price-plan-one .price-body .link {
        display: inline-flex;
        width: 100% !important;
        margin: 0 !important;
        padding: 0.875rem 1.5rem !important;
        border-radius: 12px !important;
        font-size: 1rem !important;
        font-weight: 600 !important;
        text-align: center;
        justify-content: center;
        border: 1px solid #1A1A17 !important;
        color: #1A1A17 !important;
        background: #FFFFFF !important;
        transition: all 0.25s ease !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
    }

    .price-plan-one .price-body .link.d-none {
        display: none !important;
    }

    .price-plan-one .price-body .link:hover {
        background: #1A1A17 !important;
        color: #FFFFFF !important;
    }

    /* Highlight standard CTA */
    .price-plan-standard .price-body .link {
        background: #1A1A17 !important;
        color: #FFFFFF !important;
        border: 1px solid #1A1A17 !important;
    }

    .price-plan-standard .price-body .link:hover {
        background: #000000 !important;
        border-color: #000000 !important;
    }
</style>
@endpush

        <!--  -->
        <div class="row rg-20">
            @foreach ($packages as $key => $package)
            <div class="col-xl-4 col-md-6">
                <div class="price-plan-one {{ $key == 1 ? 'price-plan-standard' : ($key >= 2 ? 'price-plan-enterprise' : '') }}">
                    <div class="price-head">
                        @if (!empty($package->icon))
                        <div class="plan-icon-container">
                            <img src="{{ asset($package->icon) }}" alt="{{ $package->name }}" />
                        </div>
                        @endif
                        <h4 class="title">{{ $package->name }}</h4>
                        <h4 class="plan-price zPrice-plan-monthly">{{ showPrice($package->monthly_price) }}</h4>
                        <h4 class="plan-price zPrice-plan-yearly d-none">{{ showPrice($package->yearly_price) }}</h4>
                    </div>
                    <div class="price-body">
                        <ul class="zList-pb-10 mb-50">
                            <li>
                                <div class="d-flex align-items-start g-10">
                                    <div class="flex-shrink-0 d-flex justify-content-center align-items-center w-15 h-15 rounded-circle bg-main-color mt-4">
                                        <img src="{{asset('assets/images/icon/features-check-icon.svg')}}" alt="{{ $package->name }}" />
                                    </div>
                                    <p class="fs-18 fw-400 lh-22 text-para-text">
                                        @if ($package->page_limit == -1)
                                            {{ __('Unlimited Pages') }}
                                        @else
                                            {{ __('Up to :n Pages', ['n' => $package->page_limit]) }}
                                        @endif
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex align-items-start g-10">
                                    <div class="flex-shrink-0 d-flex justify-content-center align-items-center w-15 h-15 rounded-circle bg-main-color mt-4">
                                        <img src="{{asset('assets/images/icon/features-check-icon.svg')}}" alt="{{ $package->name }}" />
                                    </div>
                                    <p class="fs-18 fw-400 lh-22 text-para-text">
                                        @if ($package->message_limit == -1)
                                            {{ __('Unlimited Messages/Month') }}
                                        @else
                                            {{ number_format($package->message_limit) }} {{ __('Messages/Month') }}
                                        @endif
                                    </p>
                                </div>
                            </li>
                            @foreach (($package->others ?? []) as $other)
                            <li>
                                <div class="d-flex align-items-start g-10">
                                    <div class="flex-shrink-0 d-flex justify-content-center align-items-center w-15 h-15 rounded-circle bg-main-color mt-4">
                                        <img src="{{asset('assets/images/icon/features-check-icon.svg')}}" alt="{{ $package->name }}" />
                                    </div>
                                    <p class="fs-18 fw-400 lh-26 text-para-text">{{ __($other) }}</p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @auth
                            @if (!is_null($currentPackage) && $package->id == $currentPackage->package_id)
                                @if ($currentPackage->duration_type == DURATION_MONTH)
                                    <button type="button" class="btn link zPrice-plan-monthly" disabled title="{{ __('Current Plan') }}">{{ __('Current Plan') }}</button>
                                    <button type="button" class="btn link zPrice-plan-yearly d-none ldGetStarted" data-package="{{ $package->id }}" data-duration="{{ DURATION_YEAR }}" title="{{ __('Get Started') }}">{{ __('Get Started') }}</button>
                                @else
                                    <button type="button" class="btn link zPrice-plan-monthly ldGetStarted" data-package="{{ $package->id }}" data-duration="{{ DURATION_MONTH }}" title="{{ __('Get Started') }}">{{ __('Get Started') }}</button>
                                    <button type="button" class="btn link zPrice-plan-yearly d-none" disabled title="{{ __('Current Plan') }}">{{ __('Current Plan') }}</button>
                                @endif
                            @else
                                <button type="button" class="btn link zPrice-plan-monthly ldGetStarted" data-package="{{ $package->id }}" data-duration="{{ DURATION_MONTH }}" title="{{ __('Get Started') }}">{{ __('Get Started') }}</button>
                                <button type="button" class="btn link zPrice-plan-yearly d-none ldGetStarted" data-package="{{ $package->id }}" data-duration="{{ DURATION_YEAR }}" title="{{ __('Get Started') }}">{{ __('Get Started') }}</button>
                            @endif
                        @else
                            <a href="{{ route('register', ['package' => $package->id]) }}" class="btn link zPrice-plan-monthly" title="{{ __('Get Started') }}">{{ __('Get Started') }}</a>
                            <a href="{{ route('register', ['package' => $package->id]) }}" class="btn link zPrice-plan-yearly d-none" title="{{ __('Get Started') }}">{{ __('Get Started') }}</a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach

    </div>
</section>
<!-- End Pricing -->
@push('script')
<script>
// Monthly / Yearly price toggle
$('#billingMonthly-tab').on('shown.bs.tab', function () {
    $('.zPrice-plan-yearly').addClass('d-none');
    $('.zPrice-plan-monthly').removeClass('d-none');
    $('.ldGetStarted').data('duration', {{ DURATION_MONTH }});
});
$('#billingYearly-tab').on('shown.bs.tab', function () {
    $('.zPrice-plan-monthly').addClass('d-none');
    $('.zPrice-plan-yearly').removeClass('d-none');
    $('.ldGetStarted').data('duration', {{ DURATION_YEAR }});
});

@auth
(function ($) {
    var getGatewayUrl  = "{{ route('admin.subscription.get.gateway') }}";
    var getCurrencyUrl = "{{ route('admin.subscription.get.currency') }}";
    var checkoutUrl    = "{{ route('admin.subscription.checkout') }}";
    var dashboardUrl   = "{{ route('admin.dashboard') }}";

    // Open payment modal on "Get Started"
    $(document).on('click', '.ldGetStarted', function () {
        var packageId   = $(this).data('package');
        var durationType = $(this).data('duration') || {{ DURATION_MONTH }};

        $('#ldPackageId').val(packageId);
        $('#ldDurationType').val(durationType);
        $('#ldSelectGateway').val('');
        $('#ldSelectCurrency').val('');
        $('#ldGatewayCurrencyAmount').text('');
        $('#ldCurrencyAppend').html('');
        $('#ldGatewayListBlock').html('<p class="text-para-text text-center py-20">{{ __("Loading...") }}</p>');
        $('#ldBankSection').addClass('d-none');
        $('#ldPaymentModal').modal('show');

        // Fetch gateway list for this package
        var formData = new FormData();
        formData.append('id', packageId);
        formData.append('duration_type', durationType);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
            type: 'POST',
            url: getGatewayUrl,
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status) {
                    $('#ldGatewayListBlock').html(response.data);
                } else {
                    $('#ldGatewayListBlock').html('<p class="text-danger text-center py-20">' + (response.message || 'Error loading gateways.') + '</p>');
                }
            },
            error: function () {
                $('#ldGatewayListBlock').html('<p class="text-danger text-center py-20">{{ __("Could not load payment options.") }}</p>');
            }
        });
    });

    // Gateway card selected (IDs come from gateway-list.blade.php partial)
    $(document).on('click', '#ldPaymentModal .paymentGateway', function () {
        var $btn         = $(this);
        var gatewaySlug  = $btn.data('gateway');
        var gatewayId    = $btn.data('id');
        var packageId    = $btn.data('package_id');
        var durationType = $btn.data('duration_type');

        // Highlight
        $('#ldPaymentModal .payment-item').removeClass('bd-c-main-color').addClass('bd-c-stroke');
        $('#ldPaymentModal .paymentGateway').text('{{ __("Select") }}');
        $btn.closest('.payment-item').removeClass('bd-c-stroke').addClass('bd-c-main-color');
        $btn.text('{{ __("Selected") }} ✓');

        $('#ldSelectGateway').val(gatewaySlug);
        $('#ldPackageId').val(packageId);
        $('#ldDurationType').val(durationType);

        // #bankSection lives inside the gateway-list partial
        if (gatewaySlug === 'bank') {
            $('#bankSection').removeClass('d-none');
        } else {
            $('#bankSection').addClass('d-none');
        }

        // #currencyAppend lives inside the gateway-list partial
        $('#currencyAppend').html('<li class="text-para-text fs-14">{{ __("Loading...") }}</li>');
        $.ajax({
            type: 'GET',
            url: getCurrencyUrl,
            data: { id: gatewayId },
            dataType: 'json',
            success: function (response) {
                var currencies = response.data;
                var html = '';
                if (currencies && currencies.length > 0) {
                    // #planAmount lives inside the gateway-list partial
                    var planAmount = parseFloat($('#planAmount').val()) || 0;
                    $.each(currencies, function (i, c) {
                        var converted  = (planAmount * parseFloat(c.conversion_rate)).toFixed(2);
                        var priceLabel = c.currency + ' ' + converted;
                        html += '<li class="d-flex justify-content-between align-items-center">' +
                            '<label class="d-flex align-items-center g-10 cursor-pointer">' +
                            '<input type="radio" name="ld_currency_radio" value="' + c.currency + '"' +
                            ' data-code="' + c.currency + '" data-amount="' + converted + '"' +
                            (i === 0 ? ' checked' : '') + '>' +
                            ' <span class="fs-14 fw-400 lh-16 ms-2">' + c.currency + '</span>' +
                            '</label>' +
                            '<span class="fs-14 fw-600 lh-16">' + priceLabel + '</span>' +
                            '</li>';
                        if (i === 0) {
                            $('#ldSelectCurrency').val(c.currency);
                            $('#ldGatewayCurrencyAmount').text('(' + priceLabel + ')');
                        }
                    });
                } else {
                    html = '<li><p class="text-danger fs-14">{{ __("No currency configured for this gateway.") }}</p></li>';
                }
                $('#currencyAppend').html(html);
            },
            error: function (xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : '{{ __("Failed to load currencies.") }}';
                $('#currencyAppend').html('<li><p class="text-danger fs-14">' + msg + '</p></li>');
            }
        });
    });

    // Currency radio change
    $(document).on('change', 'input[name="ld_currency_radio"]', function () {
        $('#ldSelectCurrency').val($(this).val());
        var priceLabel = $(this).data('code') + ' ' + $(this).data('amount');
        $('#ldGatewayCurrencyAmount').text('(' + priceLabel + ')');
    });

    // Bank dropdown (#bank_id is inside the gateway-list partial)
    $(document).on('change', '#bank_id', function () {
        var details = $('option:selected', this).data('details') || '';
        $('#bankDetails').find('p').html(details);
    });

    // Pay Now
    $(document).on('click', '#ldPayBtn', function () {
        if (!$('#ldSelectGateway').val()) {
            alert('{{ __("Please select a payment gateway.") }}');
            return;
        }
        if (!$('#ldSelectCurrency').val()) {
            alert('{{ __("Please select a currency.") }}');
            return;
        }
        $('#ldCheckoutForm').submit();
    });

})(jQuery);
@endauth
</script>
@endpush

{{-- Payment modal for logged-in users on landing page --}}
@auth
<style>
    #ldPaymentModal .gateway-image {
        max-width: 100px;
        max-height: 40px;
        object-fit: contain;
        display: block;
        margin: 0 auto;
    }
    #ldPaymentModal .payment-item {
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        text-align: center;
    }
</style>
<div class="modal fade" id="ldPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bd-ra-12 p-sm-30 p-15">
            <div class="d-flex justify-content-between align-items-center pb-20 mb-20" style="border-bottom:1px solid #eee">
                <h4 class="fs-20 fw-600">{{ __('Select Payment Method') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <form id="ldCheckoutForm" action="{{ route('admin.subscription.checkout') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="ldPackageId"    name="package_id">
                    <input type="hidden" id="ldSelectGateway" name="gateway">
                    <input type="hidden" id="ldSelectCurrency" name="currency">
                    <input type="hidden" id="ldDurationType"  name="duration_type" value="{{ DURATION_MONTH }}">

                    {{-- Gateway list loaded via AJAX --}}
                    <div id="ldGatewayListBlock"></div>

                    <div class="d-flex justify-content-end mt-20">
                        <button type="button" id="ldPayBtn"
                            class="border-0 bd-ra-12 py-13 px-30 bg-main-color fs-16 fw-600 text-white">
                            {{ __('Pay Now') }} <span id="ldGatewayCurrencyAmount"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endauth
@endif

@if (isset($section['testimonials_area']) && $section['testimonials_area']->status == STATUS_ACTIVE)
<!-- Start Testimonial -->
<section class="py-sm-150 py-30 bg-ld-feature-bg position-relative z-1 overflow-hidden"
    data-background="{{asset('assets/images/ld-bg-dot.svg')}}">
    <div class="container">
        <!--  -->
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center pb-55">
                    <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">
                        {{ __($section['testimonials_area']->page_title) }}</p>
                    <h4 class="lh-sm-57 lh-44 landing-section-title text-white">{{ __($section['testimonials_area']->title)
                        }}</h4>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="landing-testimonial-wrap">
            <div class="swiper ldTestiItems">
                <div class="swiper-wrapper">
                    @foreach ($testimonials as $testimonial)
                    <div class="swiper-slide">
                        <div class="landing-testimonial-item">
                            <div class="left">
                                <div class="img"><img src="{{ getFileUrl($testimonial->image) }}"
                                        alt="{{ getOption('app_name') }}" /></div>
                            </div>
                            <div class="right">
                                <p class="text">"{{ $testimonial->comment }}”</p>
                                <div
                                    class="d-flex flex-column flex-sm-row justify-content-between align-items-center g-10">
                                    <div class="content">
                                        <h4 class="name">{{ $testimonial->name }}</h4>
                                        <p class="userUrl">{{"@"}}{{$testimonial->designation }}</p>
                                    </div>
                                    <div class="rating-date text-center text-sm-end">
                                        <ul class="ld-testi-rating">
                                            {!! reviewStar($testimonial->rating) !!}
                                        </ul>
                                        <p class="fs-18 fw-400 lh-27 text-white-80">{{
                                            $testimonial->created_at?->format('d-m-Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="arrowControl">
                    <div class="swiper-button-next"><i class="fa-solid fa-angle-right"></i></div>
                    <div class="swiper-button-prev"><i class="fa-solid fa-angle-left"></i></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Testimonial -->
@endif



@if (isset($section['faqs_area']) && $section['faqs_area']->status == STATUS_ACTIVE)
<!-- Start FAQ's -->
<section class="py-sm-150 py-30 landing-faq-wrap" id="faq">
    <div class="container">
        <!--  -->
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center pb-55">
                    <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">
                        {{ __($section['faqs_area']->page_title) }}</p>
                    <h4 class="lh-sm-57 lh-44 landing-section-title text-ld-black">
                        {{ __($section['faqs_area']->title) }}
                    </h4>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="accordion zAccordion-reset zAccordion-one" id="accordionExample">
            <div class="row rg-24">
                @foreach ($faqs as $key => $faq)
                <div class="col-lg-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{$key}}" aria-controls="collapse{{$key}}">
                                {{$key + 1}}. {{ __($faq->title) }}
                            </button>
                        </h2>
                        <div id="collapse{{$key}}" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __($faq->description) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</section>
<!-- End FAQ's -->
@endif

@if (isset($section['demo_ection']) && $section['demo_ection']->status == STATUS_ACTIVE)
<section class="landing-demo-section">
    <div class="container">
        <div class="landing-demo-content-wrap">
            <div class="landing-demo-content" data-background="{{ getFileUrl($section['demo_ection']->banner_image) }}">
                <h4 class="title"><span class="d-md-block">{{ __($section['demo_ection']->title) }}</span>
                </h4>
                <a href="{{ route('login') }}" class="link">{{__('View Demo')}}</a>
            </div>
        </div>
    </div>
</section>
@endif

@endsection