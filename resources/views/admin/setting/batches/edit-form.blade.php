<div class="modal-header">
    <h5 class="modal-title">{{ __('Update Currency') }}</h5>
    <button type="button" class="border-0 btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="ajax reset" action="{{ route('admin.setting.batches.update', $batch->id) }}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    @method('PATCH')
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label for="currentPassword" class="form-label">{{ __('Name') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="primary-form-control" name="name" value="{{ $batch->name }}" required
                               placeholder="{{ __('Name') }}">
                    </div>
                </div>
            </div>
            <div class="col-12 pt-10">
                <div class="primary-form-group mt-2">
                    <div class="primary-form-group-wrap">
                        <label class="label-text-title color-heading font-medium mb-2 form-label">{{
                                            __('Status') }}</label>
                        <select name="status" id="status"
                                class="primary-form-control sf-select-without-search">
                            <option {{ $batch->status == STATUS_ACTIVE ? 'selected' : '' }} value="{{ STATUS_ACTIVE }}">{{ __('Active') }}</option>
                            <option {{ $batch->status == STATUS_DEACTIVATE ? 'selected' : '' }} value="{{ STATUS_DEACTIVATE }}">{{ __('Deactivate') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit"
                class="fs-15 fw-500 lh-25 text-black py-10 px-26 bg-cdef84 border-0 bd-ra-12 hover-bg-one">{{ __('Update') }}</button>
    </div>
</form>
