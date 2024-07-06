<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WSBS Collection</title>

    @vite(['resources/sass/app.scss', 'resources/css/landing-page.css'])
</head>

<body>
    @yield('content')
    @stack('scripts')
    @include('sweetalert::alert')
    @vite('resources/js/app.js')
</body>

</html>
