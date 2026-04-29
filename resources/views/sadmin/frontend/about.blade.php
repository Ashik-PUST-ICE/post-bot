@extends('sadmin.layouts.app')
@section('content')
@push('title') {{ $title }} @endpush

<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-40 p-15">
    <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ __($title) }}</h4>

    <form id="aboutForm" enctype="multipart/form-data">
        @csrf

        {{-- Hero --}}
        <div class="bd-one bd-c-stroke bd-ra-8 bg-white p-24 mb-20">
            <h5 class="fs-15 fw-600 text-textBlack pb-16 mb-16" style="border-bottom:1px solid #eee">{{ __('Hero Section') }}</h5>
            <div class="row rg-16">
                <div class="col-12">
                    <label class="zForm-label">{{ __('Title') }}</label>
                    <input type="text" name="title" value="{{ $about->title }}" class="form-control zForm-control">
                </div>
                <div class="col-12">
                    <label class="zForm-label">{{ __('Description') }}</label>
                    <textarea name="description" rows="3" class="form-control zForm-control">{{ $about->description }}</textarea>
                </div>
                @foreach ([1,2,3,4] as $i)
                <div class="col-md-3 col-6">
                    <label class="zForm-label">{{ __('Gallery Image') }} {{ $i }}</label>
                    @if ($about->{'image_'.$i})
                        <div class="mb-8"><img src="{{ asset($about->{'image_'.$i}) }}" style="max-height:60px;border-radius:4px;"></div>
                    @endif
                    <input type="file" name="image_{{ $i }}" class="form-control zForm-control" accept="image/*">
                </div>
                @endforeach
            </div>
        </div>

        {{-- Statistics --}}
        <div class="bd-one bd-c-stroke bd-ra-8 bg-white p-24 mb-20">
            <h5 class="fs-15 fw-600 text-textBlack pb-16 mb-16" style="border-bottom:1px solid #eee">{{ __('Statistics') }}</h5>
            <div class="row rg-16">
                @foreach ([1,2,3] as $i)
                <div class="col-md-4">
                    <label class="zForm-label">{{ __('Statistic') }} {{ $i }} — {{ __('Title') }}</label>
                    <input type="text" name="statistic_title_{{ $i }}" value="{{ $about->{'statistic_title_'.$i} }}" class="form-control zForm-control" placeholder="{{ __('e.g. 10K+') }}">
                </div>
                <div class="col-md-4">
                    <label class="zForm-label">{{ __('Statistic') }} {{ $i }} — {{ __('Description') }}</label>
                    <input type="text" name="statistic_description_{{ $i }}" value="{{ $about->{'statistic_description_'.$i} }}" class="form-control zForm-control" placeholder="{{ __('e.g. Happy Customers') }}">
                </div>
                <div class="col-md-4 d-none d-md-block"></div>
                @endforeach
            </div>
        </div>

        {{-- Mission & Vision --}}
        <div class="bd-one bd-c-stroke bd-ra-8 bg-white p-24 mb-20">
            <h5 class="fs-15 fw-600 text-textBlack pb-16 mb-16" style="border-bottom:1px solid #eee">{{ __('Mission & Vision') }}</h5>
            <div class="row rg-16">
                <div class="col-md-6">
                    <label class="zForm-label">{{ __('Mission Title') }}</label>
                    <input type="text" name="mission_title" value="{{ $about->mission_title }}" class="form-control zForm-control">
                </div>
                <div class="col-md-6">
                    <label class="zForm-label">{{ __('Mission Image') }}</label>
                    @if ($about->mission_image)
                        <div class="mb-8"><img src="{{ asset($about->mission_image) }}" style="max-height:60px;border-radius:4px;"></div>
                    @endif
                    <input type="file" name="mission_image" class="form-control zForm-control" accept="image/*">
                </div>
                <div class="col-12">
                    <label class="zForm-label">{{ __('Mission Description') }}</label>
                    <textarea name="mission_description" rows="3" class="form-control zForm-control">{{ $about->mission_description }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="zForm-label">{{ __('Vision Title') }}</label>
                    <input type="text" name="vision_title" value="{{ $about->vision_title }}" class="form-control zForm-control">
                </div>
                <div class="col-md-6">
                    <label class="zForm-label">{{ __('Vision Image') }}</label>
                    @if ($about->vision_image)
                        <div class="mb-8"><img src="{{ asset($about->vision_image) }}" style="max-height:60px;border-radius:4px;"></div>
                    @endif
                    <input type="file" name="vision_image" class="form-control zForm-control" accept="image/*">
                </div>
                <div class="col-12">
                    <label class="zForm-label">{{ __('Vision Description') }}</label>
                    <textarea name="vision_description" rows="3" class="form-control zForm-control">{{ $about->vision_description }}</textarea>
                </div>
            </div>
        </div>

        {{-- Team --}}
        <div class="bd-one bd-c-stroke bd-ra-8 bg-white p-24 mb-20">
            <h5 class="fs-15 fw-600 text-textBlack pb-16 mb-16" style="border-bottom:1px solid #eee">{{ __('Team Section') }}</h5>
            <div class="row rg-16">
                <div class="col-md-6">
                    <label class="zForm-label">{{ __('Section Title') }}</label>
                    <input type="text" name="team_section_title" value="{{ $about->team_section_title }}" class="form-control zForm-control">
                </div>
                <div class="col-md-6">
                    <label class="zForm-label">{{ __('Section Description') }}</label>
                    <input type="text" name="team_section_description" value="{{ $about->team_section_description }}" class="form-control zForm-control">
                </div>
                <div class="col-12">
                    <label class="zForm-label d-flex align-items-center justify-content-between">
                        {{ __('Team Members') }} (JSON)
                        <small class="text-para-text fw-400">{{ __('Array of: {name, designation, image, facebook_link, instagram_link, twitter_link}') }}</small>
                    </label>
                    <textarea name="team_members_json" rows="6" class="form-control zForm-control font-monospace" style="font-size:12px;">{{ json_encode($about->team_members ?? [], JSON_PRETTY_PRINT) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Core Values --}}
        <div class="bd-one bd-c-stroke bd-ra-8 bg-white p-24 mb-20">
            <h5 class="fs-15 fw-600 text-textBlack pb-16 mb-16" style="border-bottom:1px solid #eee">{{ __('Core Values') }}</h5>
            <div class="row rg-16">
                <div class="col-md-6">
                    <label class="zForm-label">{{ __('Section Title') }}</label>
                    <input type="text" name="core_value_section_title" value="{{ $about->core_value_section_title }}" class="form-control zForm-control">
                </div>
                <div class="col-md-6">
                    <label class="zForm-label">{{ __('Section Description') }}</label>
                    <input type="text" name="core_value_section_description" value="{{ $about->core_value_section_description }}" class="form-control zForm-control">
                </div>
                <div class="col-12">
                    <label class="zForm-label d-flex align-items-center justify-content-between">
                        {{ __('Core Values') }} (JSON)
                        <small class="text-para-text fw-400">{{ __('Array of: {icon, title, description}') }}</small>
                    </label>
                    <textarea name="core_values_json" rows="6" class="form-control zForm-control font-monospace" style="font-size:12px;">{{ json_encode($about->core_values ?? [], JSON_PRETTY_PRINT) }}</textarea>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" id="saveAboutBtn" class="py-12 px-30 bd-ra-4 bg-main-color text-white fw-600 border-0">
                {{ __('Save About Page') }}
            </button>
        </div>
    </form>
</div>

@push('script')
<script>
$('#aboutForm').on('submit', function (e) {
    e.preventDefault();
    const btn = $('#saveAboutBtn').prop('disabled', true).text('{{ __("Saving...") }}');
    $.ajax({
        url : '{{ route("super-admin.frontend.about.update") }}',
        type : 'POST',
        data : new FormData(this),
        processData: false, contentType: false,
        success: function (r) { toastr.success(r.message ?? '{{ __("Saved") }}'); },
        error  : function () { toastr.error('{{ __("Something went wrong") }}'); },
        complete: function () { btn.prop('disabled', false).text('{{ __("Save About Page") }}'); },
    });
});
</script>
@endpush
@endsection
