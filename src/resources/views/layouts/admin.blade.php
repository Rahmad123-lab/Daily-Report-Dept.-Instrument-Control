<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DRICS System')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full antialiased text-slate-800">

    <div class="min-h-screen flex">

        <aside class="w-64 bg-slate-900 text-white shrink-0 hidden md:flex flex-col justify-between border-r border-slate-800">
            <div>
                <div class="px-6 py-5 border-b border-slate-800 bg-slate-950">
                    <h1 class="text-xl font-black tracking-wider text-blue-500">DRICS</h1>
                    <p class="text-[10px] text-slate-400 mt-0.5 leading-tight uppercase font-medium">
                        Digital Reliability & Instrument Control System
                    </p>
                </div>

                <nav class="px-4 py-6 space-y-1">
                    <a href="{{ request()->routeIs('dashboard') ? '#' : route('dashboard') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') || true ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="text-base">📊</span> Dashboard
                    </a>

                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <span class="text-base">👷</span> Employee
                    </a>

                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <span class="text-base">🔧</span> Maintenance
                    </a>

                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <span class="text-base">🎓</span> Training
                    </a>

                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">
                        <span class="text-base">📈</span> Competency
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-slate-800 bg-slate-950/50">
                <div class="px-2 mb-3">
                    <p class="text-sm font-semibold truncate text-slate-200">
                        {{ auth()->user()->name ?? 'Rahmad Joko Susilo' }}
                    </p>
                    <p class="text-xs text-slate-400 capitalize">
                        {{ auth()->user()->role ?? 'Department Head' }}
                    </p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-white py-2 rounded-lg text-xs font-semibold transition flex items-center justify-center gap-2">
                        <span>🚪</span> Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            
            <header class="bg-white border-b border-gray-100 px-6 py-4 sticky top-0 z-10 shadow-sm">
                <div class="flex justify-between items-center">
                    
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 tracking-tight">
                            @yield('header')
                        </h2>
                        <p class="text-xs text-gray-400 font-medium">
                            Instrument & Control Department
                        </p>
                    </div>

                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-slate-700">
                            {{ auth()->user()->name ?? 'Rahmad Joko Susilo S.' }}
                        </p>
                        <p class="text-xs text-gray-400">
                            NIK: {{ auth()->user()->nik ?? '20250001' }}
                        </p>
                    </div>

                </div>
            </header>

            <main class="flex-1 p-6 md:p-8 overflow-y-auto">
                @yield('content')
            </main>

        </div>

    </div>

</body>
</html>