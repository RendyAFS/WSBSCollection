<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

    @vite(['resources/sass/app.scss', 'resources/css/nav-side-bar.css', 'resources/css/app.css', 'resources/css/bootstrap.css'])

    {{-- CSS DATATABLES --}}
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.dataTables.min.css">


    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    {{-- DataTables --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- APEX CHART --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.51.0/apexcharts.min.js"
        integrity="sha512-rgvuw7+rpm6cEJOUFmmzb2UWUVWg2VkIbmw6vMoWjbX/7CsyPgiMvrXhzZJbS0Ow1Bq/3illaZaqQej1n3AA7Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.51.0/apexcharts.min.css"
        integrity="sha512-n+A0Xug6+j9/fCBVPoCihITLoICIB2FTqjESx+kwYdF5bzpblXz11zaILuLYmN3yk2WyMTw53sah9tTiojgySg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        body {
            font-family: "Poppins", sans-serif;
            font-weight: normal;
            font-style: normal;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Page Content -->
        <div id="content-wrapper">
            <!-- Navbar -->
            @include('layouts.navbar')

            <!-- Main Content -->
            <div id="content" >
                @include('layouts.menu-mobile')
                @yield('content')
                @include('layouts.footer')
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
    @vite('resources/js/app.js')
    @stack('scripts')
</body>

</html>
