@extends('sadmin.layouts.app')
@section('content')
@push('title') {{ $title }} @endpush

<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-40 p-15">
    <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ __($title) }}</h4>

    <div class="row rg-20">
        @foreach ($sections as $section)
        <div class="col-lg-6">
            <div class="bd-one bd-c-stroke bd-ra-8 bg-white p-24">
                <div class="d-flex align-items-center justify-content-between pb-16 mb-16" style="border-bottom:1px solid #eee">
                    <div>
                        <h5 class="fs-16 fw-600 text-textBlack">{{ __(ucwords(str_replace('_', ' ', $section->section_key))) }}</h5>
                        <small class="text-para-text fs-12">{{ $section->section_key }}</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input section-status-toggle" type="checkbox" role="switch"
                               data-id="{{ $section->id }}"
                               {{ $section->status == STATUS_ACTIVE ? 'checked' : '' }}>
                    </div>
                </div>
                <form class="section-form" data-id="{{ $section->id }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row rg-12">
                        <div class="col-md-6">
                            <label class="zForm-label">{{ __('Page Title') }}</label>
                            <input type="text" name="page_title" value="{{ $section->page_title }}" class="form-control zForm-control">
                        </div>
                        <div class="col-md-6">
                            <label class="zForm-label">{{ __('Section Title') }}</label>
                            <input type="text" name="title" value="{{ $section->title }}" class="form-control zForm-control">
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Description') }}</label>
                            <textarea name="description" rows="2" class="form-control zForm-control">{{ $section->description }}</textarea>
                        </div>
                        @if (in_array($section->section_key, ['hero_area', 'demo_ection']))
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Banner Image') }}</label>
                            @if ($section->banner_image)
                                <div class="mb-8">
                                    <img src="{{ asset($section->banner_image) }}" alt="" style="max-height:60px;border-radius:4px;">
                                </div>
                            @endif
                            <input type="file" name="banner_image" class="form-control zForm-control" accept="image/*">
                        </div>
                        @endif
                        <div class="col-12 text-end">
                            <button type="submit" class="py-10 px-20 bd-ra-4 bg-main-color text-white fw-500 fs-14 border-0 section-save-btn">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('script')
<script>
$(function () {
    // Toggle status
    $(document).on('change', '.section-status-toggle', function () {
        const id     = $(this).data('id');
        const status = $(this).is(':checked') ? 1 : 0;
        const form   = new FormData();
        form.append('_token', '{{ csrf_token() }}');
        form.append('status', status);

        $.ajax({
            url  : '{{ route("super-admin.frontend.sections.update", "") }}/' + id,
            type : 'POST',
            data : form,
            processData: false,
            contentType: false,
            success: function (res) { toastr.success(res.message ?? '{{ __("Updated") }}'); },
            error  : function ()    { toastr.error('{{ __("Failed") }}'); },
        });
    });

    // Save section form
    $(document).on('submit', '.section-form', function (e) {
        e.preventDefault();
        const id  = $(this).data('id');
        const btn = $(this).find('.section-save-btn');
        btn.prop('disabled', true).text('{{ __("Saving...") }}');

        $.ajax({
            url         : '{{ route("super-admin.frontend.sections.update", "") }}/' + id,
            type        : 'POST',
            data        : new FormData(this),
            processData : false,
            contentType : false,
            success: function (res) {
                toastr.success(res.message ?? '{{ __("Saved") }}');
                btn.prop('disabled', false).text('{{ __("Save") }}');
            },
            error: function () {
                toastr.error('{{ __("Something went wrong") }}');
                btn.prop('disabled', false).text('{{ __("Save") }}');
            },
        });
    });
});
</script>
@endpush
@endsection
