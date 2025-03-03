<!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="csrf-token" content="{{ csrf_token() }}">

            <title>Admin</title>

            @vite(['resources/css/app.scss', 'resources/js/app.js', 'resources/css/loader.scss'])
        </head>
        <body>

            <main class="flex flex-col items-center">
                {{ $slot }}
            </main>

    </body>
</html>

