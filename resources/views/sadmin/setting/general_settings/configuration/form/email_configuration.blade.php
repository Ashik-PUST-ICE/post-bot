<div class="email-inbox__area">
    <div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
        <h2 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Mail Configuration') }}</h2>
        <a href="javascript:void(0);" id="sendTestMailBtn"
           class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14 d-inline-flex align-items-center g-5">
            <i class="fa fa-envelope"></i> {{ __('Send Test Mail') }}
        </a>
    </div>

    <form class="ajax" action="{{ route('super-admin.setting.settings_env.update') }}" method="POST"
        enctype="multipart/form-data" data-handler="commonResponseForModal">
        @csrf
        <div class="row rg-20 pb-20">
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3">
                <label class="zForm-label">{{ __('MAIL MAILER') }} <span class="text-danger">*</span></label>
                <input type="text" name="MAIL_MAILER" value="{{ env('MAIL_MAILER') }}"
                    class="form-control zForm-control">
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3">
                <label class="zForm-label">{{ __('MAIL HOST') }} <span class="text-danger">*</span></label>
                <input type="text" name="MAIL_HOST" value="{{ env('MAIL_HOST') }}"
                    class="form-control zForm-control">
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3">
                <label class="zForm-label">{{ __('MAIL PORT') }} <span class="text-danger">*</span></label>
                <input type="text" name="MAIL_PORT" value="{{ env('MAIL_PORT') }}"
                    class="form-control zForm-control">
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3">
                <label class="zForm-label">{{ __('MAIL USERNAME') }} <span class="text-danger">*</span></label>
                <input type="text" name="MAIL_USERNAME" value="{{ env('MAIL_USERNAME') }}"
                    class="form-control zForm-control">
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3">
                <label class="zForm-label">{{ __('MAIL PASSWORD') }} <span class="text-danger">*</span></label>
                <input type="password" name="MAIL_PASSWORD" value="{{ env('MAIL_PASSWORD') }}"
                    class="form-control zForm-control">
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3">
                <label for="MAIL_ENCRYPTION" class="zForm-label">{{ __('MAIL ENCRYPTION') }}<span
                        class="text-danger">*</span></label>
                <select name="MAIL_ENCRYPTION" class="form-control zForm-control sf-select-edit-modal">
                    <option value="tls" {{ env('MAIL_ENCRYPTION') == 'tls' ? 'selected' : '' }}>
                        {{ __('tls') }}
                    </option>
                    <option value="ssl" {{ env('MAIL_ENCRYPTION') == 'ssl' ? 'selected' : '' }}>
                        {{ __('ssl') }}
                    </option>
                </select>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3">
                <label class="zForm-label">{{ __('MAIL FROM ADDRESS') }} <span class="text-danger">*</span></label>
                <input type="text" name="MAIL_FROM_ADDRESS" value="{{ env('MAIL_FROM_ADDRESS') }}"
                    class="form-control zForm-control">
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-3">
                <label class="zForm-label">{{ __('MAIL FROM NAME') }} <span class="text-danger">*</span></label>
                <input type="text" name="MAIL_FROM_NAME" value="{{ env('MAIL_FROM_NAME') }}"
                    class="form-control zForm-control">
            </div>
        </div>
        <div class="bd-t-one bd-c-light-border pt-20 d-flex justify-content-end g-10">
            <button class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14"
                type="submit">{{ __('Save') }}</button>
        </div>
    </form>
</div>
