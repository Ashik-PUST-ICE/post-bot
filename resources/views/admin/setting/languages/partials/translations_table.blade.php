<div class="table-responsive zTable-responsive">
    <table class="table zTable">
        <thead>
        <tr>
        <tr>
            <th class="min-w-160"><div>{{ __('Key') }}</div></th>
            <th class="min-w-160"><div>{{ __('Value') }}</div></th>
            <th class="text-center w-28"><div>{{ __('Action') }}</div></th>
        </tr>
        </thead>
        <tbody id="append">
        @forelse ($translators as $key => $value)
            <tr>
                <td>
                    <textarea type="text" class="key form-control" readonly required>{!! $key !!}</textarea>
                </td>
                <td>
                    <input type="hidden" value="0" class="is_new">
                    <textarea type="text" class="val form-control" required>{!! $value !!}</textarea>
                </td>
                <td class="text-end">
                    <button type="button" class="border-0 updateLangItem fs-15 fw-500 lh-25 text-black py-10 px-26 bg-cdef84 bd-ra-12 hover-bg-one">{{ __('Update') }}</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">{{__('No Data Found')}}</td>
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
        <div class="dataTables_paginate paging_simple_numbers">
            {{-- Prev Button --}}
            <a class="paginate_button previous {{ $page == 1 ? 'disabled' : 'ajax-page' }}"
               data-page="{{ $page > 1 ? $page - 1 : '' }}"
               role="link" aria-controls="customPagination">
                <i class="fa-solid fa-angles-left"></i>
            </a>

            {{-- First Page + Dots --}}
            @if($start > 1)
                <a class="paginate_button ajax-page" data-page="1" role="link" aria-controls="customPagination">1</a>
                @if($start > 2)
                    <span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>
                @endif
            @endif

            {{-- Page Numbers --}}
            @for($p = $start; $p <= $end; $p++)
                @if($page == $p)
                    <span>
                    <a class="paginate_button current"
                       aria-current="page"
                       role="link"
                       data-page="{{ $p }}">
                        {{ $p }}
                    </a>
                </span>
                @else
                    <a class="paginate_button ajax-page"
                       data-page="{{ $p }}"
                       role="link">
                        {{ $p }}
                    </a>
                @endif
            @endfor

            {{-- Last Page + Dots --}}
            @if($end < $totalPages)
                @if($end < $totalPages - 1)
                    <span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>
                @endif
                <a class="paginate_button ajax-page"
                   data-page="{{ $totalPages }}"
                   role="link">
                    {{ $totalPages }}
                </a>
            @endif

            {{-- Next Button --}}
            <a class="paginate_button next {{ $page == $totalPages ? 'disabled' : 'ajax-page' }}"
               data-page="{{ $page < $totalPages ? $page + 1 : '' }}"
               role="link" aria-controls="customPagination">
                <i class="fa-solid fa-angles-right"></i>
            </a>
        </div>
    </div>
@endif

