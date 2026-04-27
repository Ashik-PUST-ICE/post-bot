@extends('layouts.app')
@push('title')
    {{$title}}
@endpush
@section('content')
    <div class="p-30">
        <div class="">
            <h4 class="fs-24 fw-500 lh-34 text-black pb-16">{{ __($title) }}</h4>
            <div class="">
                <form data-handler="commonResponse"
                      action="{{route('admin.roles.update.permissions', [$role->id])}}"
                      method="POST" class="ajax">
                    @csrf
                    <div class="row rg-30">
                        <!-- Modules List -->
                        <div class="col-lg-4">
                            <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30 h-100">
                                <h5 class="fs-18 fw-600 text-black mb-20">{{ __('Modules') }}</h5>
                                <div class="d-grid gap-2">
                                    @foreach($permissions as $module => $modulePermissions)
                                        <button type="button"
                                                class="fs-15 border-0 fw-500 lh-25 text-black py-10 px-26 bg-cdef84 bd-ra-12 hover-bg-one text-start module-trigger {{ $loop->first ? 'active' : '' }}"
                                                data-module="{{ $module }}">
                                            {{ moduleName($module) }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Permissions Content -->
                        <div class="col-lg-8">
                            <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
                                <!-- Search Box -->
                                <div class="mb-3">
                                    <input type="text" id="permission-search" class="form-control" placeholder="{{ __('Search permissions...') }}">
                                </div>

                                @foreach($permissions as $module => $modulePermissions)
                                    <div class="module-content {{ $loop->first ? '' : 'd-none' }}" data-module="{{ $module }}">
                                        <h5 class="fs-18 fw-600 text-black mb-20">{{ moduleName($module) }} {{ __('Permissions') }}</h5>
                                        <div class="row">
                                            @foreach($modulePermissions as $permission)
                                                <div class="col-md-6 mb-3 permission-item">
                                                    <div class="form-check">
                                                        <input
                                                            {{ in_array($permission->id, $oldPermissions) ? 'checked' : '' }}
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            name="permissions[]"
                                                            value="{{ $permission->name }}"
                                                            id="permission-{{ $permission->id }}">
                                                        <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                            {{ str_replace($module . ': ', '', $permission->name) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between pt-20">
                        <a href="{{route('admin.roles.index')}}"
                           class="d-inline-block py-13 px-25 bg-transparent bd-one bd-c-stroke-color bd-ra-10 fs-16 fw-600 lh-19 text-para-text hover-one">
                            {{__('Back')}}
                        </a>
                        <button type="submit"
                                class="fs-15 border-0 fw-500 lh-25 text-black py-10 px-26 bg-cdef84 bd-ra-12 hover-bg-one">
                            {{__('Save')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
@endsection



@push('style')
<!-- <style>
.module-trigger.active {
    background-color: #007bff !important;
    border-color: #007bff !important;
    color: white !important;
}
</style> -->
@endpush

@push('script')
    <script src="{{ asset('admin/js/roles.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Module switching
            $('.module-trigger').on('click', function() {
                const module = $(this).data('module');

                // Update active button styling
                $('.module-trigger').removeClass('active');
                $(this).addClass('active');

                // Show corresponding content
                $('.module-content').addClass('d-none');
                $(`.module-content[data-module="${module}"]`).removeClass('d-none');

                // Clear search when switching modules
                $('#permission-search').val('');
                $('.permission-item').show();
            });

            // Permission search functionality
            $('#permission-search').on('keyup', function() {
                const searchTerm = $(this).val().toLowerCase();

                $('.permission-item').each(function() {
                    const permissionText = $(this).find('.form-check-label').text().toLowerCase();
                    if (permissionText.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
@endpush
