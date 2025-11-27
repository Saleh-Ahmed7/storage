<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <!-- Bootstrap 5 CSS -->

    <!-- Your custom CSS (optional) -->
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
</head>

<body>

    <div>

        {{-- Navigation --}}

        {{-- الدروب داون لست --}}
        @include('layouts.navigation')


    </div>



    {{-- لتشغيل الدروب داون لست --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Your scripts (optional) -->
    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}

</body>
</html>
