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
