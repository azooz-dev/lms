@if ($paginator->hasPages())
    <ul class="pagination justify-content-center">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-link" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <span aria-hidden="true"><i class="la la-arrow-left"></i></span>
                <span class="sr-only">Previous</span>
            </li>
        @else
            <li class="page-link">
                <a href="{{ $paginator->previousPageUrl() }}" style="color: #233d63" rel="prev" aria-label="@lang('pagination.previous')">
                    <span aria-hidden="true"><i class="la la-arrow-left"></i></span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item active" aria-disabled="true"><span>{{ $element }}</span></li>
            @endif  

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page"><a class="page-link" href="#">{{ $page }}</a></li>
                    @else
                        <li><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                    <span aria-hidden="true"><i class="la la-arrow-right"></i></span>
                </a>
            </li>
        @else
            <li class="page-link" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span aria-hidden="true">
                    <span aria-hidden="true"><i class="la la-arrow-right"></i></span>
                    <span class="sr-only">Next</span>
                </span>
            </li>
        @endif

    </ul>
@endif


