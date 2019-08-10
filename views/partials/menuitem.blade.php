
<li>{{ $item->pagetitle }}
    @if ( isset($item->children) )
        <ul>
            @foreach ($item->children as $item)
                @include('partials.menuitem', ['item' => $item])
            @endforeach
        </ul>
    @endif
</li>