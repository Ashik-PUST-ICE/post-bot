<div class="customers__area">
    <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
        <h2 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Cookie Configuration') }}</h2>
        <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal"
                aria-label="Close"><i class="fa-solid fa-times"></i></button>
    </div>
    <form class="ajax" action="{{ route('super-admin.setting.settings_env.update') }}" method="post"
        class="form-horizontal" data-handler="commonResponseForModal">
        @csrf
        <div class="row rg-20 pb-20">
            <div class="col-12 mb-4">
                <label class="zForm-label">{{ __('Cookie Consent Text') }} </label>
                <textarea class="form-control zForm-control min-h-157" name="cookie_consent_text">{{ getOption('cookie_consent_text') }}</textarea>
            </div>
        </div>
        <div class="bd-t-one bd-c-light-border pt-20 d-flex justify-content-end g-10">
            <button class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14"
                type="submit">{{ __('Update') }}</button>
        </div>
    </form>
</div>
