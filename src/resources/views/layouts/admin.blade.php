<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'DRICS')
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 text-slate-800">

    <div class="min-h-screen flex">

        {{-- =========================
            SIDEBAR
        ========================== --}}
        <aside class="w-64 bg-slate-950 text-white flex flex-col fixed inset-y-0 left-0">

            {{-- Logo --}}
            <div class="px-6 py-6 border-b border-slate-800">

                <h1 class="text-2xl font-bold text-blue-500">
                    DRICS
                </h1>

                <p class="text-xs text-slate-400 mt-1 leading-relaxed">
                    DIGITAL RELIABILITY & INSTRUMENT
                    <br>
                    CONTROL SYSTEM
                </p>

            </div>


            {{-- Navigation --}}
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('dashboard')
                        ? 'bg-blue-600 text-white'
                        : 'text-slate-300 hover:bg-slate-800' }}">

                    <span class="text-lg">
                        📊
                    </span>

                    <span class="font-medium">
                        Dashboard
                    </span>

                </a>


                {{-- Daily Report --}}
                <a href="{{ route('daily-reports.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('daily-reports.*')
                        ? 'bg-blue-600 text-white'
                        : 'text-slate-300 hover:bg-slate-800' }}">

                    <span class="text-lg">
                        📝
                    </span>

                    <span class="font-medium">
                        Daily Report
                    </span>

                </a>


                {{-- Create Report --}}
                <a href="{{ route('daily-reports.create') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('daily-reports.create')
                        ? 'bg-blue-600 text-white'
                        : 'text-slate-300 hover:bg-slate-800' }}">

                    <span class="text-lg">
                        ➕
                    </span>

                    <span class="font-medium">
                        Create Report
                    </span>

                </a>


                {{-- Management --}}
                <div class="pt-6 pb-2 px-4">

                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Management
                    </p>

                </div>


                {{-- Employee --}}
                <a href="#"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl
                   text-slate-300 hover:bg-slate-800 transition">

                    <span class="text-lg">
                        👥
                    </span>

                    <span class="font-medium">
                        Employee
                    </span>

                </a>


                {{-- Equipment --}}
                <a href="{{ route('equipment.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('equipment.*')
                        ? 'bg-blue-600 text-white'
                        : 'text-slate-300 hover:bg-slate-800' }}">

                    <span class="text-lg">
                        🔧
                    </span>

                    <span class="font-medium">
                        Equipment
                    </span>

                </a>


                {{-- Maintenance --}}
                <a href="#"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl
                   text-slate-300 hover:bg-slate-800 transition">

                    <span class="text-lg">
                        🛠️
                    </span>

                    <span class="font-medium">
                        Maintenance
                    </span>

                </a>


                {{-- Training --}}
                <a href="#"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl
                   text-slate-300 hover:bg-slate-800 transition">

                    <span class="text-lg">
                        🎓
                    </span>

                    <span class="font-medium">
                        Training
                    </span>

                </a>


                {{-- Competency --}}
                <a href="#"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl
                   text-slate-300 hover:bg-slate-800 transition">

                    <span class="text-lg">
                        📈
                    </span>

                    <span class="font-medium">
                        Competency
                    </span>

                </a>

            </nav>


            {{-- Footer Sidebar --}}
            <div class="p-5 border-t border-slate-800">

                <div class="text-sm font-medium text-slate-300">
                    Power Plant Division
                </div>

                <div class="text-xs text-slate-500 mt-1">
                    DRICS Version 1.0.0
                </div>

            </div>

        </aside>



        {{-- =========================
            MAIN AREA
        ========================== --}}
        <div class="flex-1 ml-64 min-h-screen flex flex-col">


            {{-- =========================
                HEADER
            ========================== --}}
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-30">

                {{-- Page Title --}}
                <div>

                    <h2 class="text-xl font-bold text-slate-800">
                        @yield('header', 'Dashboard')
                    </h2>

                    <p class="text-sm text-slate-500">
                        Instrument & Control System Department
                    </p>

                </div>


                {{-- User --}}
                <div class="flex items-center gap-4">

                    <div class="text-right">

                        <div class="text-sm font-semibold text-slate-800">
                            {{ auth()->user()->name ?? 'User' }}
                        </div>

                        <div class="text-xs text-slate-500">
                            {{ auth()->user()->role ?? 'User' }}
                        </div>

                    </div>


                    {{-- Avatar --}}
                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white
                                flex items-center justify-center font-semibold">

                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}

                    </div>

                </div>

            </header>



            {{-- =========================
                CONTENT
            ========================== --}}
            <main class="flex-1 p-8">

                @if(session('success'))

                    <div class="mb-6 px-5 py-4 rounded-xl
                                bg-emerald-50 border border-emerald-200
                                text-emerald-700">

                        {{ session('success') }}

                    </div>

                @endif


                @if(session('error'))

                    <div class="mb-6 px-5 py-4 rounded-xl
                                bg-red-50 border border-red-200
                                text-red-700">

                        {{ session('error') }}

                    </div>

                @endif


                @if($errors->any())

                    <div class="mb-6 px-5 py-4 rounded-xl
                                bg-red-50 border border-red-200
                                text-red-700">

                        <ul class="list-disc ml-5 space-y-1">

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                @yield('content')

            </main>

        </div>

    </div>

</body>

</html>