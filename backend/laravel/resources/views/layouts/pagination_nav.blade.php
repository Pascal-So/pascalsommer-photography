@if(! isset($from_page_two) || ! $from_page_two || $items->currentPage() > 1)
    @if($items->hasMorePages() || $items->currentPage() != 1)
        <p id={{ $element_id ?? "" }}>
            @if($items->currentPage() > 1)
                <a href="{{ $items->url(1) }}#start-content" class="inline-block arrow-icon-small">
                    @include('icons.left_end')
                </a>
                <a href="{{ $items->previousPageUrl() }}#start-content" class="inline-block arrow-icon-small" data-shortcutkeycode="37">
                    @include('icons.left')
                </a>
            @else
                <span class="arrow-icon-small">
                    @include('icons.arrow_placeholder')
                </span>
                <span class="arrow-icon-small">
                    @include('icons.arrow_placeholder')
                </span>
            @endif

            <span style="margin: 0 10px">{{ $items->currentPage() }} / {{ $items->lastPage() }}</span>

            @if($items->hasMorePages())
                <a href="{{ $items->nextPageUrl() }}#start-content" class="inline-block arrow-icon-small" data-shortcutkeycode="39">
                    @include('icons.right')
                </a>
                <a href="{{ $items->url($items->lastPage()) }}#start-content" class="inline-block arrow-icon-small">
                    @include('icons.right_end')
                </a>
            @else
                <span class="arrow-icon-small">
                    @include('icons.arrow_placeholder')
                </span>
                <span class="arrow-icon-small">
                    @include('icons.arrow_placeholder')
                </span>
            @endif
        </p>
    @endif
@endif
