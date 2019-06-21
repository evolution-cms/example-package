<html>
    <head>
    </head>
    <body>
        Test home template


        {{ $modx->runSnippet('example#test') }}

        {{ $modx->getChunk('example#test') }}

        @php dump($topmenu) @endphp
    </body>
</html>


