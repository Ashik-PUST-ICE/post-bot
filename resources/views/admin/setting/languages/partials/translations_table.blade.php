<div class="table-responsive zTable-responsive">
    <table class="table zTable zTable-translate">
        <thead>
        <tr>
        <tr>
            <th class="min-w-160">
                <div>{{ __('Key') }}</div>
            </th>
            <th class="min-w-160">
                <div>{{ __('Value') }}</div>
            </th>
            <th class="text-end w-28">
                <div>{{ __('Action') }}</div>
            </th>
        </tr>
        </thead>
        <tbody id="append">
        @forelse ($translators as $key => $value)
            <tr>
                <td>
                    <textarea type="text" class="key form-control zForm-control" readonly required>{!! $key !!}</textarea>
                </td>
                <td>
                    <input type="hidden" value="0" class="is_new">
                    <textarea type="text" class="val form-control zForm-control" required>{!! $value !!}</textarea>
                </td>
                <td class="text-end">
                    <button type="button" class="updateLangItem py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">{{ __('Update') }}</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">
                    {{__('No data found')}}
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@php
    $totalPages = ceil($total / $perPage);
    $start = max(1, $page - 2); // show 2 pages before current
    $end = min($totalPages, $page + 2); // show 2 pages after current
@endphp

@if($totalPages > 1)
    <div class="d-flex justify-content-center mt-20 tablePagi">
        <div class="dataTables_paginate paging_simple_numbers" id="customTable_paginate">

            {{-- Prev Button --}}
            <a
                class="paginate_button previous {{ $page == 1 ? 'disabled' : 'ajax-page' }}"
                data-page="{{ $page > 1 ? $page - 1 : '' }}"
                role="link"
                tabindex="{{ $page == 1 ? '-1' : '0' }}">
                <i class="fa-solid fa-angles-left"></i>
            </a>

            <span>
                {{-- First Page + Dots --}}
                @if($start > 1)
                    <a class="paginate_button ajax-page" data-page="1" role="link" tabindex="0">1</a>
                    @if($start > 2)
                        <a class="paginate_button disabled">...</a>
                    @endif
                @endif

                {{-- Page Numbers --}}
                @for($p = $start; $p <= $end; $p++)
                    <a
                        class="paginate_button {{ $page == $p ? 'current' : 'ajax-page' }}"
                        data-page="{{ $p }}"
                        role="link"
                        aria-current="{{ $page == $p ? 'page' : '' }}"
                        tabindex="0">
                        {{ $p }}
                    </a>
                @endfor

                {{-- Last Page + Dots --}}
                @if($end < $totalPages)
                    @if($end < $totalPages - 1)
                        <a class="paginate_button disabled">...</a>
                    @endif
                    <a class="paginate_button ajax-page" data-page="{{ $totalPages }}" role="link" tabindex="0">{{ $totalPages }}</a>
                @endif
            </span>

            {{-- Next Button --}}
            <a
                class="paginate_button next {{ $page == $totalPages ? 'disabled' : 'ajax-page' }}"
                data-page="{{ $page < $totalPages ? $page + 1 : '' }}"
                role="link"
                tabindex="{{ $page == $totalPages ? '-1' : '0' }}">
                <i class="fa-solid fa-angles-right"></i>
            </a>
        </div>
    </div>
@endif
