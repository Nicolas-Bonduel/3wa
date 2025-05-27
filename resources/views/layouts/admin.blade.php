<!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="csrf-token" content="{{ csrf_token() }}">

            <title>Admin</title>

            @vite(['resources/css/app.scss', 'resources/js/app.js', 'resources/css/admin.scss'])

            <!-- jQuery -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

            <!-- DataTables -->
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
            <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        </head>
        <body id="admin">

            <livewire:notifier />

            <livewire:admin.header />

            <main>

                <livewire:admin.sidebar />

                <div id="content-wrapper">
                    {{ $slot }}
                </div>

            </main>

        </body>
</html>

