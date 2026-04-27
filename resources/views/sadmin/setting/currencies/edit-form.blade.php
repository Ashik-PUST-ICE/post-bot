<div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
    <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Update Currency') }}</h4>
    <button type="button"
            class="border-0 p-0 bg-transparent text-para-text"
            data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
</div>
<form class="ajax reset" action="{{ route('super-admin.setting.currencies.update', $currency->id) }}" method="post"
      data-handler="settingCommonHandler">
    @csrf
    @method('PATCH')

    <div class="row rg-20 pb-20">
        <div class="col-12">
            <label for="currency_code" class="zForm-label">{{ __('Currency ISO Code') }} <span
                    class="text-danger">*</span></label>
            <select class="sf-select-edit-modal primary-form-control" name="currency_code">
                @foreach (getCurrency() as $code => $currencyItem)
                    <option value="{{ $code }}" {{ $code==$currency->currency_code ? 'selected' : '' }}>{{
                                $currencyItem }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-12">
            <label for="symbol" class="zForm-label">{{ __('Symbol') }}<span
                    class="text-danger">*</span></label>
            <input type="text" class="form-control zForm-control" name="symbol" placeholder="{{ __('e.g. $') }}"
                   value="{{ $currency->symbol }}" required>
        </div>
        <div class="col-12">
            <label for="currency_placement" class="zForm-label">{{ __('Currency Placement') }}<span
                    class="text-danger">*</span></label>
            <select class="sf-select-without-search primary-form-control" name="currency_placement">
                <option value="">--{{ __('Select Option') }}--</option>
                <option {{ $currency->currency_placement == 'before' ? 'selected' : '' }} value="before">
                    {{ __('Before Amount (e.g. $100)') }}</option>
                <option {{ $currency->currency_placement == 'after' ? 'selected' : '' }} value="after">
                    {{ __('After Amount (e.g. 100$)') }}</option>
            </select>
        </div>
        <div class="col-12 mt-4">
            <div class="d-flex form-check ps-0">
                <div class="zCheck form-check form-switch">
                    <input class="form-check-input mt-0" value="1" name="current_currency" {{
                            $currency->current_currency == STATUS_ACTIVE ? 'checked' : '' }} type="checkbox"
                           id="flexCheckChecked-{{ $currency->id }}">
                </div>
                <label class="form-check-label ps-3 d-flex" for="flexCheckChecked-{{ $currency->id }}">
                    {{ __('Current Currency') }}
                </label>
            </div>
        </div>
    </div>

    <div class="d-flex g-12 flex-wrap pt-20 bd-t-one bd-c-light-border">
        <button
            class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14"
                type="submit">{{
                    __('Update') }}</button>
    </div>
</form>
