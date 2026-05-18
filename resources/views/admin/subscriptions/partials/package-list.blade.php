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

<div class="row rg-20 justify-content-center">
    @foreach ($packages as $key => $package)

    <div class="col-xl-4 col-md-6">
        <form class="ajax" action="{{ route('admin.subscription.get.gateway') }}" method="post"
            enctype="multipart/form-data" data-handler="setPaymentModal">
            @csrf

            <input type="hidden" name="id" value="{{ $package->id }}">
            <input type="hidden" class="plan_type " name="duration_type" id="duration_type" value="1">


            <div
                class="price-plan-one {{ $key > 0 ? ($key == 1 ? 'price-plan-standard' : 'price-plan-enterprise') : '' }} {{ $package->is_popular == STATUS_ACTIVE ? 'price-plan-popular' : '' }}">
                <div class=" price-head">
                    @if (!empty($package->icon))
                    <div class="plan-icon-container">
                        <img src="{{ asset($package->icon) }}" alt="{{ $package->name }}" />
                    </div>
                    @endif
                    <h4 class="title">{{ $package->name }}</h4>
                    <h4 class="plan-price zPrice-plan-monthly">{{ showPrice($package->monthly_price) }}</h4>
                    <h4 class="plan-price zPrice-plan-yearly">{{ showPrice($package->yearly_price) }}</h4>
                </div>
                <div class="price-body">
                    <ul class="zList-pb-10 mb-50">
                        <li>
                            <div class="d-flex align-items-start g-10">
                                <div
                                    class="flex-shrink-0 d-flex justify-content-center align-items-center w-15 h-15 rounded-circle bg-main-color mt-4">
                                    <img src="{{asset('assets/images/icon/features-check-icon.svg')}}"
                                        alt="{{ $package->name }}" />
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
                                <div
                                    class="flex-shrink-0 d-flex justify-content-center align-items-center w-15 h-15 rounded-circle bg-main-color mt-4">
                                    <img src="{{asset('assets/images/icon/features-check-icon.svg')}}"
                                        alt="{{ $package->name }}" />
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
                                <div
                                    class="flex-shrink-0 d-flex justify-content-center align-items-center w-15 h-15 rounded-circle bg-main-color mt-4">
                                    <img src="{{asset('assets/images/icon/features-check-icon.svg')}}"
                                        alt="{{ $package->name }}" />
                                </div>
                                <p class="fs-18 fw-400 lh-26 text-para-text">{{ __($other) }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @if ($package->id == $currentPackage?->package_id)
                    <button type="submit"
                        class="btn link zPrice-plan-monthly {{ $currentPackage->duration_type == DURATION_MONTH ? 'bg-main-color text-bg-danger' : 'd-none' }}"
                        {{ $currentPackage->duration_type == DURATION_MONTH ? 'disabled' : '' }}
                        title="{{ $currentPackage->duration_type == DURATION_MONTH ? __('Current Plan') : __('Subscribe Now') }}">
                        {{ $currentPackage->duration_type == DURATION_MONTH ? __('Current Plan') : __('Subscribe Now') }}
                    </button>
                    <button type="submit"
                        class="btn link zPrice-plan-yearly {{ $currentPackage->duration_type == DURATION_YEAR ? 'bg-main-color text-bg-danger' : 'd-none' }}"
                        {{ $currentPackage->duration_type == DURATION_YEAR ? 'disabled' : '' }}
                        title="{{ $currentPackage->duration_type == DURATION_YEAR ? __('Current Plan') : __('Subscribe Now') }}">
                        {{ $currentPackage->duration_type == DURATION_YEAR ? __('Current Plan') : __('Subscribe Now') }}
                    </button>
                    @else
                    <button type="submit" class="btn link" title="{{ __('Subscribe Now') }}">
                        {{ __('Subscribe Now') }}
                    </button>
                    @endif
                </div>
            </div>
        </form>
    </div>

    @endforeach
</div>
