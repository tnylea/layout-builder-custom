<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head', ['cdn' => true])
    </head>
    <body>
        {{ $slot }}
    </body>
</html>