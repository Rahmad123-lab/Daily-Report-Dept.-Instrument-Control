<aside class="w-64 bg-slate-950 text-white flex flex-col border-r border-slate-800">

    {{-- =========================================================
        LOGO
    ========================================================== --}}
    <div class="px-6 py-6 border-b border-slate-800">

        <div class="flex items-center gap-3">

            {{-- DRICS Mark --}}
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 shadow-lg shadow-blue-600/20">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-white"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M12 2v20"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H7"/>
                </svg>

            </div>


            <div>

                <h1 class="text-xl font-bold tracking-wide text-white">
                    DRICS
                </h1>

                <p class="text-[9px] font-medium tracking-wider text-slate-500 uppercase">
                    Digital Reliability
                </p>

            </div>

        </div>


        <div class="mt-4">

            <p class="text-[10px] leading-relaxed text-slate-500 uppercase tracking-wide">
                Instrument & Control
                <br>
                Management System
            </p>

        </div>

    </div>


    {{-- =========================================================
        NAVIGATION
    ========================================================== --}}
    <nav class="flex-1 overflow-y-auto px-3 py-5">

        <div class="space-y-1">


            {{-- =================================================
                DASHBOARD
            ================================================== --}}
            <a
                href="{{ route('dashboard') }}"
                class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition-all duration-200
                {{ request()->routeIs('dashboard')
                    ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20'
                    : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 shrink-0
                    {{ request()->routeIs('dashboard')
                        ? 'text-white'
                        : 'text-slate-500 group-hover:text-slate-300' }}"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <rect width="7" height="9" x="3" y="3" rx="1"/>
                    <rect width="7" height="5" x="14" y="3" rx="1"/>
                    <rect width="7" height="9" x="14" y="12" rx="1"/>
                    <rect width="7" height="5" x="3" y="16" rx="1"/>
                </svg>

                <span>
                    Dashboard
                </span>

            </a>


            {{-- =================================================
                EMPLOYEE
            ================================================== --}}
            <div class="group flex cursor-not-allowed items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-600">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 shrink-0 text-slate-700"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>

                <span>
                    Employee
                </span>

                <span class="ml-auto rounded-md bg-slate-900 px-1.5 py-0.5 text-[9px] font-medium uppercase tracking-wide text-slate-600">
                    Soon
                </span>

            </div>


            {{-- =================================================
                EQUIPMENT
            ================================================== --}}
            <a
                href="{{ route('equipment.index') }}"
                class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition-all duration-200
                {{ request()->routeIs('equipment.*')
                    ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20'
                    : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 shrink-0
                    {{ request()->routeIs('equipment.*')
                        ? 'text-white'
                        : 'text-slate-500 group-hover:text-slate-300' }}"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M12 2v20"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H7"/>
                </svg>

                <span>
                    Equipment
                </span>

            </a>


            {{-- =================================================
                MAINTENANCE
                Temporary: Equipment Master
            ================================================== --}}
           <a
    href="{{ route('maintenance.index') }}"
    class="flex items-center gap-3 px-4 py-2.5 rounded-xl
           transition
           {{ request()->routeIs('maintenance.*')
                ? 'bg-blue-600 text-white'
                : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
>
    <svg
        class="w-5 h-5 shrink-0"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="1.8"
            d="M11.42 6.02a5.5 5.5 0 0 0 6.56 6.56l-8.1 8.1a2.12 2.12 0 0 1-3-3l8.1-8.1a5.5 5.5 0 0 0-3.56-3.56Z"
        />
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="1.8"
            d="m14.5 4.5 5 5M16.5 2.5l5 5"
        />
    </svg>

    <span>Maintenance</span>
</a>


            {{-- =================================================
                DAILY REPORT
            ================================================== --}}
            <a
                href="{{ route('daily-reports.index') }}"
                class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition-all duration-200
                {{ request()->routeIs('daily-reports.*')
                    ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20'
                    : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 shrink-0
                    {{ request()->routeIs('daily-reports.*')
                        ? 'text-white'
                        : 'text-slate-500 group-hover:text-slate-300' }}"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z"/>
                    <path d="M14 2v6h6"/>
                    <path d="M8 13h8"/>
                    <path d="M8 17h5"/>
                    <path d="M8 9h2"/>
                </svg>

                <span>
                    Daily Report
                </span>

            </a>


            {{-- =================================================
                TRAINING
            ================================================== --}}
            <div class="group flex cursor-not-allowed items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-600">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 shrink-0 text-slate-700"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M22 10 12 5 2 10l10 5 10-5Z"/>
                    <path d="M6 12.5V17c3.3 2.3 8.7 2.3 12 0v-4.5"/>
                    <path d="M22 10v6"/>
                </svg>

                <span>
                    Training
                </span>

                <span class="ml-auto rounded-md bg-slate-900 px-1.5 py-0.5 text-[9px] font-medium uppercase tracking-wide text-slate-600">
                    Soon
                </span>

            </div>


            {{-- =================================================
                COMPETENCY
            ================================================== --}}
            <div class="group flex cursor-not-allowed items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-600">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 shrink-0 text-slate-700"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M9 11l3 3L22 4"/>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                </svg>

                <span>
                    Competency
                </span>

                <span class="ml-auto rounded-md bg-slate-900 px-1.5 py-0.5 text-[9px] font-medium uppercase tracking-wide text-slate-600">
                    Soon
                </span>

            </div>

        </div>

    </nav>


    {{-- =========================================================
        USER SECTION
    ========================================================== --}}
    <div class="border-t border-slate-800 p-4">

        <div class="flex items-center gap-3">

            {{-- User Avatar --}}
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-800 text-sm font-semibold text-slate-300">

                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

            </div>


            <div class="min-w-0 flex-1">

                <p class="truncate text-sm font-semibold text-white">
                    {{ auth()->user()->name }}
                </p>

                <p class="mt-0.5 text-xs text-slate-500">
                    {{ ucfirst(auth()->user()->role ?? 'User') }}
                </p>

            </div>

        </div>


        {{-- Logout --}}
        <form
            method="POST"
            action="{{ route('logout') }}"
            class="mt-4"
        >

            @csrf

            <button
                type="submit"
                class="group flex w-full items-center justify-center gap-2 rounded-xl
                       border border-slate-800 bg-slate-900 px-4 py-2.5
                       text-sm font-medium text-slate-400
                       transition-all duration-200
                       hover:border-red-900/50
                       hover:bg-red-950/30
                       hover:text-red-400"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" x2="9" y1="12" y2="12"/>
                </svg>

                <span>
                    Logout
                </span>

            </button>

        </form>

    </div>

</aside>