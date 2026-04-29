@extends('sadmin.layouts.app')
@section('content')
@push('title') {{ $title }} @endpush

@php
    $isService      = $type === 'service';
    $isCoreFeature  = $type === 'core_feature';
    $isTestimonial  = $type === 'testimonial';
    $isFaq          = $type === 'faq';
    $hasDescription = in_array($type, ['core_feature', 'choose_us', 'faq', 'testimonial', 'service']);
    $hasImage       = in_array($type, ['feature', 'service', 'core_feature', 'choose_us', 'testimonial']);
@endphp

<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-40 p-15">
    <div class="d-flex align-items-center justify-content-between pb-16">
        <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __($title) }}</h4>
        <button class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white"
                type="button" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa fa-plus"></i> {{ __('Add New') }}
        </button>
    </div>

    <div class="row rg-16">
        @forelse ($items as $item)
        <div class="col-lg-4 col-md-6">
            <div class="bd-one bd-c-stroke bd-ra-8 bg-white p-20 h-100">
                @if ($item->image && $hasImage)
                    <div class="mb-12">
                        <img src="{{ asset($item->image) }}" alt="{{ $item->title }}" style="max-height:60px;max-width:100%;border-radius:4px;object-fit:cover;">
                    </div>
                @endif
                @if ($item->name)
                    <p class="fs-12 fw-600 text-main-color mb-4">{{ $item->name }}</p>
                @endif
                <h6 class="fs-15 fw-600 text-textBlack mb-8">{{ $item->title }}</h6>
                @if ($item->sub_title)
                    <p class="fs-13 fw-500 text-para-text mb-6">{{ $item->sub_title }}</p>
                @endif
                @if ($item->description)
                    <p class="fs-13 fw-400 text-para-text mb-8" style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">{{ $item->description }}</p>
                @endif
                @if ($isTestimonial && $item->rating)
                    <div class="mb-8">
                        @for ($s = 1; $s <= 5; $s++)
                            <i class="fa-star {{ $s <= $item->rating ? 'fa-solid text-warning' : 'fa-regular text-para-text' }}"></i>
                        @endfor
                    </div>
                @endif
                <div class="d-flex align-items-center justify-content-between mt-auto pt-12" style="border-top:1px solid #f0f0f0">
                    <span class="zBadge {{ $item->status == STATUS_ACTIVE ? 'zBadge-done' : 'zBadge-inactive' }}">
                        {{ $item->status == STATUS_ACTIVE ? __('Active') : __('Inactive') }}
                    </span>
                    <div class="d-flex cg-8">
                        <button class="btn-edit p-0 border-0 bg-transparent text-main-color" data-id="{{ $item->id }}" title="{{ __('Edit') }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-delete p-0 border-0 bg-transparent text-danger" data-id="{{ $item->id }}" title="{{ __('Delete') }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-60 text-para-text">
                <i class="fa-solid fa-inbox fs-40 mb-12"></i>
                <p>{{ __('No items yet. Click "Add New" to get started.') }}</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bd-ra-4 p-20">
            <div class="d-flex align-items-center justify-content-between pb-16 mb-16" style="border-bottom:1px solid #eee">
                <h5 class="fs-16 fw-600 text-textBlack mb-0">{{ __('Add') }} {{ __($title) }}</h5>
                <button type="button" class="p-0 border-0 bg-transparent" data-bs-dismiss="modal"><i class="fa-solid fa-times text-para-text"></i></button>
            </div>
            <form id="addForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <div class="row rg-16">
                    @if ($isService)
                    <div class="col-md-6">
                        <label class="zForm-label">{{ __('Brand Name') }}</label>
                        <input type="text" name="name" class="form-control zForm-control" placeholder="{{ __('e.g. Automation') }}">
                    </div>
                    @endif
                    <div class="{{ $isService ? 'col-md-6' : 'col-12' }}">
                        <label class="zForm-label">{{ __('Title') }} <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control zForm-control" required>
                    </div>
                    @if ($isService)
                    <div class="col-12">
                        <label class="zForm-label">{{ __('Sub Title') }}</label>
                        <input type="text" name="sub_title" class="form-control zForm-control">
                    </div>
                    @endif
                    @if ($hasDescription)
                    <div class="col-12">
                        <label class="zForm-label">{{ __('Description') }}</label>
                        <textarea name="description" rows="3" class="form-control zForm-control"></textarea>
                    </div>
                    @endif
                    @if ($isService)
                    <div class="col-12">
                        <label class="zForm-label">{{ __('Bullet Points') }} <small class="text-para-text">({{ __('one per line') }})</small></label>
                        <textarea name="others" rows="4" class="form-control zForm-control" placeholder="{{ __('Feature 1') }}&#10;{{ __('Feature 2') }}"></textarea>
                    </div>
                    @endif
                    @if ($isTestimonial)
                    <div class="col-md-6">
                        <label class="zForm-label">{{ __('Rating') }}</label>
                        <select name="rating" class="form-control zForm-control">
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} {{ __('Star') }}</option>
                            @endfor
                        </select>
                    </div>
                    @endif
                    @if ($hasImage)
                    <div class="col-md-6">
                        <label class="zForm-label">{{ __('Image') }}</label>
                        <input type="file" name="image" class="form-control zForm-control" accept="image/*">
                    </div>
                    @endif
                    <div class="{{ $isTestimonial || $hasImage ? 'col-md-3' : 'col-md-4' }}">
                        <label class="zForm-label">{{ __('Sort Order') }}</label>
                        <input type="number" name="sort_order" value="0" class="form-control zForm-control" min="0">
                    </div>
                    <div class="{{ $isTestimonial || $hasImage ? 'col-md-3' : 'col-md-4' }}">
                        <label class="zForm-label">{{ __('Status') }}</label>
                        <select name="status" class="form-control zForm-control">
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>
                <div class="text-end pt-16 mt-16" style="border-top:1px solid #eee">
                    <button type="button" class="py-10 px-20 bd-ra-4 border-0 bg-eaeaea text-para-text fw-500 me-8" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" id="addBtn" class="py-10 px-24 bd-ra-4 bg-main-color text-white fw-500 border-0">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bd-ra-4 p-20">
            <div class="d-flex align-items-center justify-content-between pb-16 mb-16" style="border-bottom:1px solid #eee">
                <h5 class="fs-16 fw-600 text-textBlack mb-0">{{ __('Edit') }} {{ __($title) }}</h5>
                <button type="button" class="p-0 border-0 bg-transparent" data-bs-dismiss="modal"><i class="fa-solid fa-times text-para-text"></i></button>
            </div>
            <form id="editForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="edit_id" id="editId">
                <div class="row rg-16">
                    @if ($isService)
                    <div class="col-md-6">
                        <label class="zForm-label">{{ __('Brand Name') }}</label>
                        <input type="text" name="name" id="editName" class="form-control zForm-control">
                    </div>
                    @endif
                    <div class="{{ $isService ? 'col-md-6' : 'col-12' }}">
                        <label class="zForm-label">{{ __('Title') }} <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="editTitle" class="form-control zForm-control" required>
                    </div>
                    @if ($isService)
                    <div class="col-12">
                        <label class="zForm-label">{{ __('Sub Title') }}</label>
                        <input type="text" name="sub_title" id="editSubTitle" class="form-control zForm-control">
                    </div>
                    @endif
                    @if ($hasDescription)
                    <div class="col-12">
                        <label class="zForm-label">{{ __('Description') }}</label>
                        <textarea name="description" id="editDescription" rows="3" class="form-control zForm-control"></textarea>
                    </div>
                    @endif
                    @if ($isService)
                    <div class="col-12">
                        <label class="zForm-label">{{ __('Bullet Points') }} <small class="text-para-text">({{ __('one per line') }})</small></label>
                        <textarea name="others" id="editOthers" rows="4" class="form-control zForm-control"></textarea>
                    </div>
                    @endif
                    @if ($isTestimonial)
                    <div class="col-md-6">
                        <label class="zForm-label">{{ __('Rating') }}</label>
                        <select name="rating" id="editRating" class="form-control zForm-control">
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} {{ __('Star') }}</option>
                            @endfor
                        </select>
                    </div>
                    @endif
                    @if ($hasImage)
                    <div class="col-md-6">
                        <label class="zForm-label">{{ __('Image') }}</label>
                        <div id="editCurrentImg" class="mb-8"></div>
                        <input type="file" name="image" class="form-control zForm-control" accept="image/*">
                    </div>
                    @endif
                    <div class="col-md-3">
                        <label class="zForm-label">{{ __('Sort Order') }}</label>
                        <input type="number" name="sort_order" id="editSortOrder" value="0" class="form-control zForm-control" min="0">
                    </div>
                    <div class="col-md-3">
                        <label class="zForm-label">{{ __('Status') }}</label>
                        <select name="status" id="editStatus" class="form-control zForm-control">
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>
                <div class="text-end pt-16 mt-16" style="border-top:1px solid #eee">
                    <button type="button" class="py-10 px-20 bd-ra-4 border-0 bg-eaeaea text-para-text fw-500 me-8" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" id="editBtn" class="py-10 px-24 bd-ra-4 bg-main-color text-white fw-500 border-0">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
const routes = {
    store  : '{{ route("super-admin.frontend.contents.store") }}',
    update : '{{ route("super-admin.frontend.contents.update", "") }}',
    delete : '{{ route("super-admin.frontend.contents.delete", "") }}',
    info   : '{{ route("super-admin.frontend.contents.info", "") }}',
};

$(function () {
    // Add
    $('#addForm').on('submit', function (e) {
        e.preventDefault();
        const btn = $('#addBtn').prop('disabled', true).text('{{ __("Saving...") }}');
        $.ajax({
            url : routes.store, type : 'POST',
            data : new FormData(this), processData: false, contentType: false,
            success: function (r) {
                toastr.success(r.message);
                $('#addModal').modal('hide');
                setTimeout(() => location.reload(), 800);
            },
            error: function () { toastr.error('{{ __("Something went wrong") }}'); },
            complete: function () { btn.prop('disabled', false).text('{{ __("Save") }}'); },
        });
    });

    // Edit — load data
    $(document).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $.get(routes.info + '/' + id, function (r) {
            const d = r.data;
            $('#editId').val(d.id);
            $('#editTitle').val(d.title ?? '');
            if ($('#editName').length)       $('#editName').val(d.name ?? '');
            if ($('#editSubTitle').length)   $('#editSubTitle').val(d.sub_title ?? '');
            if ($('#editDescription').length)$('#editDescription').val(d.description ?? '');
            if ($('#editOthers').length)     $('#editOthers').val(d.others_text ?? '');
            if ($('#editRating').length)     $('#editRating').val(d.rating ?? 5);
            $('#editSortOrder').val(d.sort_order ?? 0);
            $('#editStatus').val(d.status ?? 1);
            if (d.image) {
                $('#editCurrentImg').html('<img src="{{ asset('') }}' + d.image + '" style="max-height:50px;border-radius:4px;">');
            }
            $('#editModal').modal('show');
        });
    });

    // Edit — submit
    $('#editForm').on('submit', function (e) {
        e.preventDefault();
        const id  = $('#editId').val();
        const btn = $('#editBtn').prop('disabled', true).text('{{ __("Updating...") }}');
        $.ajax({
            url : routes.update + '/' + id, type : 'POST',
            data : new FormData(this), processData: false, contentType: false,
            success: function (r) {
                toastr.success(r.message);
                $('#editModal').modal('hide');
                setTimeout(() => location.reload(), 800);
            },
            error: function () { toastr.error('{{ __("Something went wrong") }}'); },
            complete: function () { btn.prop('disabled', false).text('{{ __("Update") }}'); },
        });
    });

    // Delete
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        if (!confirm('{{ __("Are you sure you want to delete this item?") }}')) return;
        $.post(routes.delete + '/' + id, { _token: '{{ csrf_token() }}' }, function (r) {
            toastr.success(r.message);
            setTimeout(() => location.reload(), 800);
        }).fail(function () { toastr.error('{{ __("Something went wrong") }}'); });
    });
});
</script>
@endpush
@endsection
