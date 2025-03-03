<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'My Electronics' }}</title>

        @vite(['resources/css/app.scss', 'resources/js/app.js', 'resources/css/loader.scss'])
    </head>
    <body>

        <livewire:header.header />

        <main class="flex flex-col items-center">
            {{ $slot }}
        </main>

        <livewire:footer.footer />

    </body>
</html>
