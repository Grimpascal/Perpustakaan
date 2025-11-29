<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex">
    <x-navbar-admin></x-navbar-admin>
    <div id="KontenUtama" class="flex-1 p-4 lg:ml-5">
        @yield('content')
    </div>
</body>
</html>