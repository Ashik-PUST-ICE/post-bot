@extends('sadmin.layouts.app')
@section('content')
@push('title') {{ $title }} @endpush

<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-40 p-15">
    <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ __($title) }}</h4>

    <form id="policiesForm">
        @csrf

        <ul class="nav nav-tabs zTab-reset mb-20" id="policyTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#privacyTab" type="button">{{ __('Privacy Policy') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#returnTab" type="button">{{ __('Return Policy') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tncTab" type="button">{{ __('Terms & Conditions') }}</button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="privacyTab">
                <div class="bd-one bd-c-stroke bd-ra-8 bg-white p-24">
                    <h5 class="fs-15 fw-600 text-textBlack pb-12 mb-12" style="border-bottom:1px solid #eee">{{ __('Privacy Policy') }}</h5>
                    <textarea name="privacy_policy" id="privacy_policy_editor" rows="20" class="form-control zForm-control">{{ $privacy_policy }}</textarea>
                </div>
            </div>
            <div class="tab-pane fade" id="returnTab">
                <div class="bd-one bd-c-stroke bd-ra-8 bg-white p-24">
                    <h5 class="fs-15 fw-600 text-textBlack pb-12 mb-12" style="border-bottom:1px solid #eee">{{ __('Return Policy') }}</h5>
                    <textarea name="return_policy" id="return_policy_editor" rows="20" class="form-control zForm-control">{{ $return_policy }}</textarea>
                </div>
            </div>
            <div class="tab-pane fade" id="tncTab">
                <div class="bd-one bd-c-stroke bd-ra-8 bg-white p-24">
                    <h5 class="fs-15 fw-600 text-textBlack pb-12 mb-12" style="border-bottom:1px solid #eee">{{ __('Terms & Conditions') }}</h5>
                    <textarea name="t_and_c" id="t_and_c_editor" rows="20" class="form-control zForm-control">{{ $t_and_c }}</textarea>
                </div>
            </div>
        </div>

        <div class="text-end pt-20">
            <button type="submit" id="savePoliciesBtn" class="py-12 px-30 bd-ra-4 bg-main-color text-white fw-600 border-0">
                {{ __('Save Policies') }}
            </button>
        </div>
    </form>
</div>

@push('script')
<script>
$('#policiesForm').on('submit', function (e) {
    e.preventDefault();
    const btn = $('#savePoliciesBtn').prop('disabled', true).text('{{ __("Saving...") }}');
    $.ajax({
        url  : '{{ route("super-admin.frontend.policies.update") }}',
        type : 'POST',
        data : $(this).serialize(),
        success: function (r) { toastr.success(r.message ?? '{{ __("Saved") }}'); },
        error  : function () { toastr.error('{{ __("Something went wrong") }}'); },
        complete: function () { btn.prop('disabled', false).text('{{ __("Save Policies") }}'); },
    });
});
</script>
@endpush
@endsection
