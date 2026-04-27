<div class="customers__area">
    <div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
        <h2 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Google analytics configuration') }}</h2>
        <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal"
                aria-label="Close"><i class="fa-solid fa-times"></i></button>
    </div>
    <form class="ajax" action="{{ route('super-admin.setting.settings_env.update') }}" method="post"
        class="form-horizontal" data-handler="commonResponseForModal">
        @csrf
        <div class="pb-20">
            <div class="col-lg-12">
                <label class="zForm-label">{{ __('Google Analytics Tracking Id') }} </label>
                <input type="text" min="0" max="100" step="any" name="google_analytics_tracking_id"
                    value="{{ getOption('google_analytics_tracking_id') }}" class="form-control zForm-control">
            </div>
        </div>
        <div class="bd-t-one bd-c-light-border pt-20 d-flex justify-content-end g-10">
            <button class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14"
                type="submit">{{ __('Update') }}</button>
        </div>
    </form>
</div>
