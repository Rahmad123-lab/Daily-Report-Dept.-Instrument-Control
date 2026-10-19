@extends('layouts.app')

@section('title', 'Dashboard DRICS')

@section('header')
    Dashboard
@endsection

@section('content')

<div class="space-y-6">

    {{-- =========================================================
        WELCOME
    ========================================================== --}}
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl p-6 text-white shadow-sm">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <p class="text-sm text-slate-400 mb-1">
                    Instrument & Control System
                </p>

                <h1 class="text-2xl md:text-3xl font-bold">
                    Welcome, {{ auth()->user()->name ?? 'User' }}
                </h1>

                <p class="mt-2 text-sm text-slate-300">
                    Monitor daily reports, maintenance activity, and equipment reliability.
                </p>
            </div>

            <div class="hidden md:flex items-center justify-center
                        w-14 h-14 rounded-2xl
                        bg-blue-600/20 border border-blue-500/20">

                <svg class="w-7 h-7 text-blue-400"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M3 13h2l2-7 4 14 3-9 2 4h5"/>

                </svg>

            </div>

        </div>

    </div>


    {{-- =========================================================
        KPI CARDS
    ========================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        {{-- Total Employee --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Total Employee
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $totalEmployees }}
                    </h2>
                </div>

                <div class="w-10 h-10 rounded-xl bg-blue-50
                            flex items-center justify-center">

                    <svg class="w-5 h-5 text-blue-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M17 20h5v-2a4 4 0 00-4-4h-1
                                 M9 20H4v-2a4 4 0 014-4h1
                                 M12 12a4 4 0 100-8 4 4 0 000 8z
                                 M17 8a3 3 0 110 6
                                 M7 8a3 3 0 100 6"/>

                    </svg>

                </div>

            </div>

            <p class="mt-4 text-xs text-slate-400">
                Registered employees
            </p>

        </div>


        {{-- Active Maintenance --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Active Maintenance
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $activeMaintenance }}
                    </h2>
                </div>

                <div class="w-10 h-10 rounded-xl bg-amber-50
                            flex items-center justify-center">

                    <svg class="w-5 h-5 text-amber-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M14.7 6.3a4 4 0 01-5.4 5.4L4 17v3h3l5.3-5.3
                                 a4 4 0 005.4-5.4l-2 2-2-2 2-2z"/>

                    </svg>

                </div>

            </div>

            <p class="mt-4 text-xs text-slate-400">
                Currently active maintenance
            </p>

        </div>


        {{-- Total Reports --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Total Reports
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $totalReports }}
                    </h2>
                </div>

                <div class="w-10 h-10 rounded-xl bg-indigo-50
                            flex items-center justify-center">

                    <svg class="w-5 h-5 text-indigo-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2z
                                 M14 3v5h5
                                 M9 13h6
                                 M9 17h6
                                 M9 9h2"/>

                    </svg>

                </div>

            </div>

            <p class="mt-4 text-xs text-slate-400">
                Daily reports recorded
            </p>

        </div>


        {{-- Published Reports --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Published Reports
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $publishedReports }}
                    </h2>
                </div>

                <div class="w-10 h-10 rounded-xl bg-emerald-50
                            flex items-center justify-center">

                    <svg class="w-5 h-5 text-emerald-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M5 12l4 4L19 6"/>

                    </svg>

                </div>

            </div>

            <p class="mt-4 text-xs text-slate-400">
                Reports completed and published
            </p>

        </div>

    </div>


    {{-- =========================================================
        REPORT + MAINTENANCE
    ========================================================== --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Daily Report Summary --}}
        <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm">

            <div class="px-6 py-5 border-b border-slate-200">

                <div class="flex items-center justify-between">

                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            Daily Report Overview
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Current report status
                        </p>
                    </div>

                    <a href="{{ route('daily-reports.index') }}"
                       class="text-sm font-medium text-blue-600 hover:text-blue-700">

                        View Reports →

                    </a>

                </div>

            </div>


            <div class="p-6">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Draft --}}
                    <div class="rounded-xl border border-slate-200 p-5">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-sm text-slate-500">
                                    Draft Reports
                                </p>

                                <p class="mt-2 text-2xl font-bold text-slate-800">
                                    {{ $draftReports }}
                                </p>
                            </div>

                            <div class="w-10 h-10 rounded-xl bg-slate-100
                                        flex items-center justify-center">

                                <svg class="w-5 h-5 text-slate-600"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="1.8"
                                          d="M12 20h9
                                             M16.5 3.5a2.1 2.1 0 013 3L8 18l-4 1 1-4z"/>

                                </svg>

                            </div>

                        </div>

                        <div class="mt-4 h-2 bg-slate-100 rounded-full overflow-hidden">

                            @php
                                $draftPercentage = $totalReports > 0
                                    ? ($draftReports / $totalReports) * 100
                                    : 0;
                            @endphp

                            <div class="h-full bg-slate-500 rounded-full"
                                 style="width: {{ $draftPercentage }}%">
                            </div>

                        </div>

                    </div>


                    {{-- Published --}}
                    <div class="rounded-xl border border-slate-200 p-5">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-sm text-slate-500">
                                    Published Reports
                                </p>

                                <p class="mt-2 text-2xl font-bold text-slate-800">
                                    {{ $publishedReports }}
                                </p>
                            </div>

                            <div class="w-10 h-10 rounded-xl bg-emerald-50
                                        flex items-center justify-center">

                                <svg class="w-5 h-5 text-emerald-600"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="1.8"
                                          d="M5 12l4 4L19 6"/>

                                </svg>

                            </div>

                        </div>

                        <div class="mt-4 h-2 bg-slate-100 rounded-full overflow-hidden">

                            @php
                                $publishedPercentage = $totalReports > 0
                                    ? ($publishedReports / $totalReports) * 100
                                    : 0;
                            @endphp

                            <div class="h-full bg-emerald-500 rounded-full"
                                 style="width: {{ $publishedPercentage }}%">
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Maintenance --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">

            <div class="px-6 py-5 border-b border-slate-200">

                <h2 class="text-lg font-bold text-slate-800">
                    Maintenance
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Current maintenance activity
                </p>

            </div>


            <div class="p-6 space-y-4">

                @forelse($maintenanceItems as $item)

                    <div class="flex items-start gap-3">

                        <div class="mt-1 w-2 h-2 rounded-full bg-amber-500">
                        </div>

                        <div class="min-w-0">

                            <p class="text-sm font-semibold text-slate-800 truncate">
                                {{ $item->equipment->equipment_name ?? 'Equipment' }}
                            </p>

                            <p class="text-xs text-slate-500 mt-1">
                                {{ $item->maintenance_type }}
                            </p>

                        </div>

                    </div>

                @empty

                    <div class="py-8 text-center">

                        <p class="text-sm text-slate-500">
                            No active maintenance.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- =========================================================
        QUICK ACCESS
    ========================================================== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        {{-- Equipment --}}
        <a href="{{ route('equipment.index') }}"
           class="group bg-white rounded-2xl border border-slate-200
                  p-5 shadow-sm hover:shadow-md hover:border-blue-200 transition">

            <div class="flex items-center gap-4">

                <div class="w-11 h-11 rounded-xl bg-blue-50
                            flex items-center justify-center">

                    <svg class="w-5 h-5 text-blue-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M10.3 4.3a2.3 2.3 0 013.4 0l1.1 1.1
                                 a2.3 2.3 0 010 3.4l-1 1 1.9 1.9
                                 1-1a2.3 2.3 0 013.4 0l1.1 1.1
                                 a2.3 2.3 0 010 3.4l-4.4 4.4
                                 a2.3 2.3 0 01-3.4 0l-1.1-1.1
                                 a2.3 2.3 0 010-3.4l1-1-1.9-1.9-1 1
                                 a2.3 2.3 0 01-3.4 0l-1.1-1.1
                                 a2.3 2.3 0 010-3.4z"/>

                    </svg>

                </div>

                <div>

                    <h3 class="font-semibold text-slate-800
                               group-hover:text-blue-600">
                        Equipment
                    </h3>

                    <p class="text-xs text-slate-500 mt-1">
                        Manage equipment master
                    </p>

                </div>

            </div>

        </a>


        {{-- Daily Reports --}}
        <a href="{{ route('daily-reports.index') }}"
           class="group bg-white rounded-2xl border border-slate-200
                  p-5 shadow-sm hover:shadow-md hover:border-blue-200 transition">

            <div class="flex items-center gap-4">

                <div class="w-11 h-11 rounded-xl bg-indigo-50
                            flex items-center justify-center">

                    <svg class="w-5 h-5 text-indigo-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M6 3h9l3 3v15H6a2 2 0 01-2-2V5a2 2 0 012-2z
                                 M14 3v4h4
                                 M8 11h8
                                 M8 15h8
                                 M8 19h5"/>

                    </svg>

                </div>

                <div>

                    <h3 class="font-semibold text-slate-800
                               group-hover:text-indigo-600">
                        Daily Reports
                    </h3>

                    <p class="text-xs text-slate-500 mt-1">
                        Review department reports
                    </p>

                </div>

            </div>

        </a>


        {{-- Maintenance --}}
        <a href="#"
           class="group bg-white rounded-2xl border border-slate-200
                  p-5 shadow-sm hover:shadow-md hover:border-amber-200 transition">

            <div class="flex items-center gap-4">

                <div class="w-11 h-11 rounded-xl bg-amber-50
                            flex items-center justify-center">

                    <svg class="w-5 h-5 text-amber-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M14.7 6.3a4 4 0 01-5.4 5.4L4 17v3h3l5.3-5.3
                                 a4 4 0 005.4-5.4l-2 2-2-2 2-2z"/>

                    </svg>

                </div>

                <div>

                    <h3 class="font-semibold text-slate-800
                               group-hover:text-amber-600">
                        Maintenance
                    </h3>

                    <p class="text-xs text-slate-500 mt-1">
                        Monitor maintenance activity
                    </p>

                </div>

            </div>

        </a>

    </div>

</div>

@endsection