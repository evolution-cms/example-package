<html>
    <head>
    </head>
    <body>
        Test home template

        {{ $modx->runSnippet('example#test') }}

        {{ $modx->getChunk('example#test') }}


        <h1>Test Menu</h1>
        @php dump($menu) @endphp

        <hr>


        <ul>
        @foreach ($menu as $item)
            @include('partials.menuitem', ['item' => $item])
        @endforeach
        </ul>


    </body>
</html>


