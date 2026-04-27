

        @if ($paginator->hasPages())
        <ul class="sf-local-pagination">
            @if ($paginator->onFirstPage())
            <li>
                <a href="#" class="prev disabled">
                    <img src="{{{asset('assets/images/icon/double-angle-left-disabled.svg')}}}" alt="" class="disabled-img" />
                </a>
            </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" class="prev">
                        <img src="{{asset('assets/images/icon/double-angle-left.svg')}}" alt="" class="non-disabled-img" />
                    </a>
                </li>
            @endif
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li><a href="#" class="disabled">{{ $element }}</a></li>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="active">
                                    <a class="bg-main-color text-bg-dark">{{ $page }}</a>
                                </li>
                            @else
                                <li class="">
                                    <a class="" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}" class="next">
                            <img src="{{asset('assets/images/icon/double-angle-right.svg')}}" alt="" class="non-disabled-img" />
                        </a>
                    </li>
                @else
                    <li class="disabled">
                        <a href="#" class="next">
                            <img src="{{asset('assets/images/icon/double-angle-right-disabled.svg')}}" alt="" class="disabled-img" />
                        </a>
                    </li>
                @endif
        </ul>
         @endif
