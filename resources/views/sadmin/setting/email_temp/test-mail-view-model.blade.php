<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h5 class="fs-18 fw-600 lh-22 text-textBlack">{{__('Send Test Mail')}}</h5>
    <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal"
            aria-label="Close"><i class="fa-solid fa-times"></i></button>
</div>
<form class="" action="{{ route('super-admin.setting.send-test-mail', $template->id) }}" method="POST"
    data-handler="commonResponseForModal">
    @csrf
    <input type="hidden" name="id">

        <div class="row rg-20 pb-20">
            <!--  -->
            <div class="col-12">
                <label for="sunject" class="zForm-label">{{__('Give me your email')}}<span
                        class="text-red">*</span></label>
                <input type="email" name="email" class="form-control zForm-control" placeholder="Give me test mail"
                    required />
            </div>
        </div>

    <div class="d-flex justify-content-end">
        <button type="submit"
                class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white">{{
            __('Send') }}</button>
    </div>
</form>
