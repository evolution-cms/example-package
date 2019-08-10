
<li>{{ $item->pagetitle }}
    @if (is_countable($item->children) && count($item->children) > 0 )
        <ul>
            @foreach ($item->children as $item)
                @include('partials.menuitem', ['item' => $item])
            @endforeach
        </ul>
    @endif
</li>