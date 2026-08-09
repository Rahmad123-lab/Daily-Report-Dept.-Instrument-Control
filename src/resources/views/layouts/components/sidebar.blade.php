<aside class="w-64 bg-slate-950 text-white flex flex-col">

    {{-- Logo --}}
    <div class="px-6 py-6 border-b border-slate-800">

        <h1 class="text-2xl font-bold text-blue-500">
            DRICS
        </h1>

        <p class="mt-1 text-[10px] leading-tight text-slate-400 uppercase">
            Digital Reliability & Instrument
            <br>
            Control System
        </p>

    </div>


    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-6 space-y-2">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 rounded-xl px-4 py-3
           {{ request()->routeIs('dashboard')
                ? 'bg-blue-600 text-white'
                : 'text-slate-300 hover:bg-slate-800' }}">

            <span>📊</span>
            <span>Dashboard</span>

        </a>


        {{-- Employee --}}
        <a href="#"
           class="flex items-center gap-3 rounded-xl px-4 py-3
           text-slate-300 hover:bg-slate-800">

            <span>👤</span>
            <span>Employee</span>

        </a>


        {{-- Maintenance --}}
        <a href="#"
           class="flex items-center gap-3 rounded-xl px-4 py-3
           text-slate-300 hover:bg-slate-800">

            <span>🔧</span>
            <span>Maintenance</span>

        </a>


        {{-- Daily Report --}}
        <a href="{{ route('daily-reports.index') }}"
           class="flex items-center gap-3 rounded-xl px-4 py-3
           {{ request()->routeIs('daily-reports.*')
                ? 'bg-blue-600 text-white'
                : 'text-slate-300 hover:bg-slate-800' }}">

            <span>📝</span>
            <span>Daily Report</span>

        </a>


        {{-- Training --}}
        <a href="#"
           class="flex items-center gap-3 rounded-xl px-4 py-3
           text-slate-300 hover:bg-slate-800">

            <span>🎓</span>
            <span>Training</span>

        </a>


        {{-- Competency --}}
        <a href="#"
           class="flex items-center gap-3 rounded-xl px-4 py-3
           text-slate-300 hover:bg-slate-800">

            <span>📋</span>
            <span>Competency</span>

        </a>


        {{-- Equipment --}}
        <a href="{{ route('equipment.index') }}"
           class="flex items-center gap-3 rounded-xl px-4 py-3
           {{ request()->routeIs('equipment.*')
                ? 'bg-blue-600 text-white'
                : 'text-slate-300 hover:bg-slate-800' }}">

            <span>⚙️</span>
            <span>Equipment</span>

        </a>

    </nav>


    {{-- User --}}
    <div class="border-t border-slate-800 p-4">

        <div class="text-sm font-semibold">
            {{ auth()->user()->name }}
        </div>

        <div class="text-xs text-slate-400">
            {{ ucfirst(auth()->user()->role ?? 'User') }}
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">

            @csrf

            <button
                type="submit"
                class="w-full rounded-xl bg-red-950/40 px-4 py-2
                       text-sm text-red-400 hover:bg-red-900/50">

                Logout

            </button>

        </form>

    </div>

</aside>