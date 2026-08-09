<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'DR-ICS')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    @include('layouts.components.sidebar')

    {{-- Content --}}
    <div class="flex flex-col flex-1">

        {{-- Navbar --}}
        @include('layouts.components.navbar')

        <main class="flex-1 p-6">

            @yield('content')

        </main>

    </div>

</div>

</body>
</html>