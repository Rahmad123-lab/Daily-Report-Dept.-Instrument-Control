<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'DRICS')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>


<body class="bg-slate-100 text-slate-800">

<div class="min-h-screen flex">


    {{-- =========================================================
        SIDEBAR
    ========================================================== --}}
    <aside
    class="fixed inset-y-0 left-0 z-40
           flex h-screen w-64 flex-col
           overflow-hidden
           bg-slate-950 text-white">


        {{-- =====================================================
            BRAND
        ====================================================== --}}
        <div class="px-6 py-6 border-b border-slate-800">

            <div class="flex items-center gap-3">

                {{-- Logo --}}
                <div
                    class="w-10 h-10
                           rounded-xl
                           bg-blue-600
                           flex items-center justify-center
                           shadow-sm"
                >

                    <svg
                        class="w-5 h-5 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 3v18M3 12h18"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M5.64 5.64l12.72 12.72M18.36 5.64L5.64 18.36"
                        />

                    </svg>

                </div>


                {{-- Brand --}}
                <div>

                    <h1
                        class="text-xl
                               font-bold
                               tracking-wide
                               text-white"
                    >
                        DRICS
                    </h1>

                    <p
                        class="text-[10px]
                               uppercase
                               tracking-wider
                               text-slate-500"
                    >
                        Reliability System
                    </p>

                </div>

            </div>


            <p
                class="text-[11px]
                       text-slate-400
                       mt-4
                       leading-relaxed"
            >
                Digital Reliability & Instrument
                Control System
            </p>

        </div>



        {{-- =====================================================
            NAVIGATION
        ====================================================== --}}
 <nav
    class="flex-1 min-h-0 overflow-y-auto
           p-4 space-y-1
           [scrollbar-width:thin]
           [scrollbar-color:#334155_transparent]"
>


            {{-- =================================================
                OPERATIONS
            ================================================== --}}
            <div class="px-3 pt-1 pb-2">

                <p
                    class="text-[10px]
                           font-semibold
                           text-slate-500
                           uppercase
                           tracking-widest"
                >
                    Operations
                </p>

            </div>


            {{-- =================================================
                DASHBOARD
            ================================================== --}}
            <a
                href="{{ route('dashboard') }}"
                class="
                    flex items-center gap-3
                    px-4 py-3
                    rounded-xl
                    transition
                    group

                    {{ request()->routeIs('dashboard')
                        ? 'bg-blue-600 text-white shadow-sm'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                    }}
                "
            >

                <svg
                    class="w-5 h-5 shrink-0
                        {{ request()->routeIs('dashboard')
                            ? 'text-white'
                            : 'text-slate-500 group-hover:text-slate-300'
                        }}"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M4 19V9m5 10V5m6 14v-7m5 7V3"
                    />

                </svg>


                <span class="font-medium">
                    Dashboard
                </span>

            </a>



            {{-- =================================================
                DAILY REPORT
            ================================================== --}}
            <a
                href="{{ route('daily-reports.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-3
                    rounded-xl
                    transition
                    group

                    {{ request()->routeIs('daily-reports.index')
                        || request()->routeIs('daily-reports.show')
                        || request()->routeIs('daily-reports.edit')
                        ? 'bg-blue-600 text-white shadow-sm'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                    }}
                "
            >

                <svg
                    class="w-5 h-5 shrink-0
                        {{ request()->routeIs('daily-reports.index')
                            || request()->routeIs('daily-reports.show')
                            || request()->routeIs('daily-reports.edit')
                            ? 'text-white'
                            : 'text-slate-500 group-hover:text-slate-300'
                        }}"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M6 3h9l3 3v15H6V3Z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-width="1.8"
                        d="M9 11h6M9 15h6M9 7h3"
                    />

                </svg>


                <span class="font-medium">
                    Daily Report
                </span>

            </a>



            {{-- =================================================
                CREATE REPORT
            ================================================== --}}
            <a
                href="{{ route('daily-reports.create') }}"
                class="
                    flex items-center gap-3
                    px-4 py-3
                    rounded-xl
                    transition
                    group

                    {{ request()->routeIs('daily-reports.create')
                        ? 'bg-blue-600 text-white shadow-sm'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                    }}
                "
            >

                <svg
                    class="w-5 h-5 shrink-0
                        {{ request()->routeIs('daily-reports.create')
                            ? 'text-white'
                            : 'text-slate-500 group-hover:text-slate-300'
                        }}"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M12 5v14M5 12h14"
                    />

                </svg>


                <span class="font-medium">
                    Create Report
                </span>

            </a>



            {{-- =================================================
                ASSET MANAGEMENT
            ================================================== --}}
            <div class="px-3 pt-7 pb-2">

                <p
                    class="text-[10px]
                           font-semibold
                           text-slate-500
                           uppercase
                           tracking-widest"
                >
                    Asset Management
                </p>

            </div>



            {{-- =================================================
                EQUIPMENT
            ================================================== --}}
            <a
                href="{{ route('equipment.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-3
                    rounded-xl
                    transition
                    group

                    {{ request()->routeIs('equipment.*')
                        ? 'bg-blue-600 text-white shadow-sm'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                    }}
                "
            >

                <svg
                    class="w-5 h-5 shrink-0
                        {{ request()->routeIs('equipment.*')
                            ? 'text-white'
                            : 'text-slate-500 group-hover:text-slate-300'
                        }}"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M14.7 6.3a4.5 4.5 0 0 0 5.7 5.7l-8.9 8.9a2.1 2.1 0 0 1-3-3l8.9-8.9a4.5 4.5 0 0 0-2.7-2.7Z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-width="1.8"
                        d="m15 4 5 5"
                    />

                </svg>


                <span class="font-medium">
                    Equipment
                </span>

            </a>



            {{-- =================================================
                MAINTENANCE
            ================================================== --}}
            <a
                href="{{ route('maintenance.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-3
                    rounded-xl
                    transition
                    group

                    {{ request()->routeIs('maintenance.*')
                        ? 'bg-blue-600 text-white shadow-sm'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                    }}
                "
            >

                <svg
                    class="w-5 h-5 shrink-0
                        {{ request()->routeIs('maintenance.*')
                            ? 'text-white'
                            : 'text-slate-500 group-hover:text-slate-300'
                        }}"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M14.7 6.3a4.5 4.5 0 0 0 5.7 5.7l-8.9 8.9a2.1 2.1 0 0 1-3-3l8.9-8.9a4.5 4.5 0 0 0-2.7-2.7Z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-width="1.8"
                        d="m5 5 4 4M4 4l3 3"
                    />

                </svg>


                <span class="font-medium">
                    Maintenance
                </span>

            </a>



            {{-- =================================================
                PEOPLE & DEVELOPMENT
            ================================================== --}}
            <div class="px-3 pt-7 pb-2">

                <p
                    class="text-[10px]
                           font-semibold
                           text-slate-500
                           uppercase
                           tracking-widest"
                >
                    People & Development
                </p>

            </div>



           {{-- =================================================
    EMPLOYEE MANAGEMENT
================================================== --}}
<a
    href="{{ route('admin.employees.index') }}"
    @if (request()->routeIs('admin.employees.*'))
        aria-current="page"
    @endif
    class="
        flex items-center gap-3
        px-4 py-3
        rounded-xl
        transition
        group
        {{ request()->routeIs('admin.employees.*')
            ? 'bg-blue-600 text-white shadow-sm'
            : 'text-slate-400 hover:bg-slate-800 hover:text-white'
        }}
    "
>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        width="20"
        height="20"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="shrink-0"
        aria-hidden="true"
    >
        <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" />
        <circle cx="9.5" cy="7" r="4" />
        <path d="M17 11a4 4 0 1 0 0-8M21 21v-2a4 4 0 0 0-3-3.87" />
    </svg>

    <span class="font-medium">
        Employee
    </span>
</a>


          {{-- TRAINING MANAGEMENT --}}
<a
    href="{{ route('trainings.index') }}"
    @if(request()->routeIs('trainings.*'))
        aria-current="page"
    @endif
    class="
        group flex items-center gap-3 rounded-xl
        px-4 py-3 transition
        {{ request()->routeIs('trainings.*')
            ? 'bg-blue-600 text-white shadow-sm'
            : 'text-slate-400 hover:bg-slate-800 hover:text-white'
        }}
    "
>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        width="20"
        height="20"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="shrink-0 {{
            request()->routeIs('trainings.*')
                ? 'text-white'
                : 'text-slate-500 group-hover:text-slate-300'
        }}"
        aria-hidden="true"
    >
        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
        <path d="M8 7h8M8 11h8"/>
    </svg>

    <span class="font-medium">Training</span>
</a>



{{-- COMPETENCY MANAGEMENT --}}
<a
    href="{{ route('competencies.index') }}"
    @if(request()->routeIs('competencies.*') ||
        request()->routeIs('employee-competencies.*'))
        aria-current="page"
    @endif
    class="
        group flex items-center gap-3 rounded-xl
        px-4 py-3 transition
        {{ request()->routeIs('competencies.*') ||
           request()->routeIs('employee-competencies.*')
            ? 'bg-blue-600 text-white shadow-sm'
            : 'text-slate-400 hover:bg-slate-800 hover:text-white'
        }}
    "
>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        width="20"
        height="20"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="shrink-0 {{
            request()->routeIs('competencies.*') ||
            request()->routeIs('employee-competencies.*')
                ? 'text-white'
                : 'text-slate-500 group-hover:text-slate-300'
        }}"
        aria-hidden="true"
    >
        <path d="m12 15 2 2 4-4"/>
        <path d="M3 6.5 12 3l9 3.5v5c0 5-4 8-9 9.5-5-1.5-9-4.5-9-9.5v-5Z"/>
    </svg>

    <span class="font-medium">Competency</span>
</a>


        {{-- =====================================================
            SIDEBAR FOOTER
        ====================================================== --}}
        <div class="p-4 border-t border-slate-800">

            <div class="shrink-0 p-4 border-t border-slate-800 bg-slate-950">

                <div
                    class="w-10 h-10
                           rounded-xl
                           bg-blue-600
                           text-white
                           flex items-center justify-center
                           font-semibold
                           shrink-0"
                >

                    {{ strtoupper(
                        substr(
                            auth()->user()->name ?? 'U',
                            0,
                            1
                        )
                    ) }}

                </div>


                <div class="min-w-0 flex-1">

                    <p
                        class="text-sm
                               font-semibold
                               text-white
                               truncate"
                    >
                        {{ auth()->user()->name ?? 'User' }}
                    </p>


                    <p
                        class="text-xs
                               text-slate-500
                               truncate"
                    >
                        {{ auth()->user()->role ?? 'User' }}
                    </p>

                </div>

            </div>

            {{-- Logout Button --}}
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf

                <button
                    type="submit"
                    class="group flex w-full items-center gap-3 rounded-xl
                           border border-slate-800 px-4 py-3
                           text-sm font-semibold text-slate-300
                           transition duration-200
                           hover:border-red-500/30
                           hover:bg-red-500/10
                           hover:text-red-400
                           focus:outline-none focus:ring-2
                           focus:ring-red-500 focus:ring-offset-2
                           focus:ring-offset-slate-950"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="shrink-0"
                        aria-hidden="true"
                    >
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>

                    <span>Logout</span>
                </button>
            </form>

            {{-- System Information --}}

            <div class="px-3 pt-4">

                <div
                    class="text-[11px]
                           font-medium
                           text-slate-500"
                >
                    Power Plant Division
                </div>


                <div
                    class="text-[10px]
                           text-slate-600
                           mt-1"
                >
                    DRICS Version 1.0.0
                </div>

            </div>

        </div>

    </aside>



    {{-- =========================================================
        MAIN AREA
    ========================================================== --}}
    <div
        class="flex-1
               ml-64
               min-h-screen
               flex flex-col"
    >


        {{-- =====================================================
            HEADER
        ====================================================== --}}
        <header
            class="h-20
                   bg-white
                   border-b border-slate-200
                   flex items-center
                   justify-between
                   px-8
                   sticky top-0
                   z-30"
        >


            {{-- Page Title --}}
            <div>

                <h2
                    class="text-xl
                           font-bold
                           text-slate-800"
                >
                    @yield('header', 'Dashboard')
                </h2>


                <p
                    class="text-sm
                           text-slate-500
                           mt-0.5"
                >
                    Instrument & Control System Department
                </p>

            </div>



            {{-- User --}}
            <div class="flex items-center gap-3">

                <div class="text-right">

                    <div
                        class="text-sm
                               font-semibold
                               text-slate-800"
                    >
                        {{ auth()->user()->name ?? 'User' }}
                    </div>


                    <div
                        class="text-xs
                               text-slate-500
                               mt-0.5"
                    >
                        {{ auth()->user()->role ?? 'User' }}
                    </div>

                </div>


                {{-- Avatar --}}
                <div
                    class="w-10 h-10
                           rounded-xl
                           bg-blue-600
                           text-white
                           flex items-center
                           justify-center
                           font-semibold
                           shadow-sm"
                >

                    {{ strtoupper(
                        substr(
                            auth()->user()->name ?? 'U',
                            0,
                            1
                        )
                    ) }}

                </div>

            </div>

        </header>



        {{-- =====================================================
            CONTENT
        ====================================================== --}}
        <main
            class="flex-1
                   p-8"
        >


            {{-- =================================================
                SUCCESS MESSAGE
            ================================================== --}}
            @if(session('success'))

                <div
                    class="mb-6
                           flex items-start gap-3
                           px-5 py-4
                           rounded-xl
                           bg-emerald-50
                           border border-emerald-200
                           text-emerald-700"
                >

                    <svg
                        class="w-5 h-5 mt-0.5 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m5 12 4 4L19 6"
                        />

                    </svg>


                    <span class="text-sm font-medium">
                        {{ session('success') }}
                    </span>

                </div>

            @endif



            {{-- =================================================
                ERROR MESSAGE
            ================================================== --}}
            @if(session('error'))

                <div
                    class="mb-6
                           flex items-start gap-3
                           px-5 py-4
                           rounded-xl
                           bg-red-50
                           border border-red-200
                           text-red-700"
                >

                    <svg
                        class="w-5 h-5 mt-0.5 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v3m0 4h.01M10.29 3.86 2.82 17a2 2 0 0 0 1.72 0l7.46-13.14a2 2 0 0 0-3.44 0Z"
                        />

                    </svg>


                    <span class="text-sm font-medium">
                        {{ session('error') }}
                    </span>

                </div>

            @endif



            {{-- =================================================
                VALIDATION ERRORS
            ================================================== --}}
            @if($errors->any())

                <div
                    class="mb-6
                           px-5 py-4
                           rounded-xl
                           bg-red-50
                           border border-red-200
                           text-red-700"
                >

                    <div class="flex items-start gap-3">

                        <svg
                            class="w-5 h-5 mt-0.5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v3m0 4h.01M10.29 3.86 2.82 17a2 2 0 0 0 1.72 0l7.46-13.14a2 2 0 0 0-3.44 0Z"
                            />

                        </svg>


                        <div>

                            <p class="text-sm font-semibold">
                                Please check the following errors:
                            </p>


                            <ul
                                class="mt-2
                                       list-disc
                                       ml-5
                                       space-y-1
                                       text-sm"
                            >

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>

            @endif



            {{-- =================================================
                PAGE CONTENT
            ================================================== --}}
            @yield('content')

        </main>

    </div>

</div>

</body>

</html>