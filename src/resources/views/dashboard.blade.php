
@extends('layouts.app')

@section('title', 'Dashboard DRICS')
@section('header', 'Dashboard')

@section('content')

@php
    $equipmentStatusConfig = [
        [
            'label' => 'Active',
            'count' => $activeEquipments,
            'color' => 'bg-emerald-500',
            'text' => 'text-emerald-700',
            'background' => 'bg-emerald-50',
        ],
        [
            'label' => 'Standby',
            'count' => $standbyEquipments,
            'color' => 'bg-blue-500',
            'text' => 'text-blue-700',
            'background' => 'bg-blue-50',
        ],
        [
            'label' => 'Maintenance',
            'count' => $maintenanceEquipments,
            'color' => 'bg-amber-500',
            'text' => 'text-amber-700',
            'background' => 'bg-amber-50',
        ],
        [
            'label' => 'Breakdown',
            'count' => $breakdownEquipments,
            'color' => 'bg-red-500',
            'text' => 'text-red-700',
            'background' => 'bg-red-50',
        ],
        [
            'label' => 'Decommission',
            'count' => $decommissionedEquipments,
            'color' => 'bg-slate-500',
            'text' => 'text-slate-700',
            'background' => 'bg-slate-100',
        ],
    ];

    $dailyReportStatusConfig = [
        [
            'label' => 'Draft',
            'count' => $draftReports,
            'color' => 'text-slate-700',
            'background' => 'bg-slate-100',
        ],
        [
            'label' => 'Published',
            'count' => $publishedReports,
            'color' => 'text-emerald-700',
            'background' => 'bg-emerald-50',
        ],
    ];

    $maintenanceActivityConfig = [
        [
            'label' => 'Open',
            'count' => $openDailyActivities,
            'color' => 'text-red-600',
            'background' => 'bg-red-50',
        ],
        [
            'label' => 'Progress',
            'count' => $progressDailyActivities,
            'color' => 'text-amber-600',
            'background' => 'bg-amber-50',
        ],
        [
            'label' => 'Done',
            'count' => $completedDailyActivities,
            'color' => 'text-emerald-600',
            'background' => 'bg-emerald-50',
        ],
    ];

    $competencyCoverageWidth = min(
        100,
        max(0, $competencyAssessmentRate)
    );

    $reportCountWidth = $totalReports > 0
        ? min(100, ($publishedReports / $totalReports) * 100)
        : 0;
@endphp


<div class="space-y-6">

    {{-- =====================================================
         WELCOME SECTION
    ====================================================== --}}

    <section class="overflow-hidden rounded-2xl bg-gradient-to-r
                    from-slate-950 via-slate-900 to-slate-800
                    p-6 text-white shadow-sm">

        <div class="flex flex-col gap-5 md:flex-row
                    md:items-center md:justify-between">

            <div class="max-w-2xl">

                <p class="text-sm font-medium text-blue-300">
                    Instrument & Control System Department
                </p>

                <h1 class="mt-2 text-2xl font-bold tracking-tight
                           md:text-3xl">
                    Welcome, {{ auth()->user()->name ?? 'User' }}
                </h1>

                <p class="mt-2 max-w-xl text-sm leading-6 text-slate-300">
                    Monitor operational activities, equipment reliability,
                    maintenance performance, and employee competency development.
                </p>

                <p class="mt-4 text-xs text-slate-400">
                    {{ now()->format('l, d F Y') }}
                    <span class="mx-1">·</span>
                    {{ auth()->user()->position ?? 'Instrument & Control' }}
                </p>

            </div>

            <div class="hidden h-16 w-16 shrink-0 items-center
                        justify-center rounded-2xl border
                        border-blue-400/20 bg-blue-500/10 md:flex">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-9 w-9 text-blue-400"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.5">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M3 13h2l2-7 4 14 3-9 2 4h5"/>

                </svg>

            </div>

        </div>

    </section>


    {{-- =====================================================
         OPERATIONAL KPI
    ====================================================== --}}

    <section>

        <div class="mb-4">
            <h2 class="text-lg font-bold text-slate-800">
                Operational Overview
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Ringkasan kondisi operasional departemen.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2
                    xl:grid-cols-3">

            {{-- Active Employees --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Active Employees
                        </p>

                        <h3 class="mt-3 text-3xl font-bold text-slate-800">
                            {{ number_format($totalEmployees) }}
                        </h3>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center
                                rounded-xl bg-blue-50 text-blue-600">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M17 20h5v-2a4 4 0 00-4-4h-1
                                     M9 20H4v-2a4 4 0 014-4h1
                                     M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                        </svg>

                    </div>

                </div>

                <p class="mt-4 text-xs text-slate-400">
                    Employees with active accounts
                </p>

                @if($isAdmin)
                    <a href="{{ route('admin.employees.index') }}"
                       class="mt-3 inline-block text-xs font-semibold
                              text-blue-600 hover:text-blue-800">
                        Manage Employees →
                    </a>
                @endif

            </div>


            {{-- Total Reports --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Daily Reports
                        </p>

                        <h3 class="mt-3 text-3xl font-bold text-slate-800">
                            {{ number_format($totalReports) }}
                        </h3>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center
                                rounded-xl bg-indigo-50 text-indigo-600">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2z
                                     M14 3v5h5M9 13h6M9 17h6"/>
                        </svg>

                    </div>

                </div>

                <p class="mt-4 text-xs text-slate-400">
                    Total daily reports recorded
                </p>

                <a href="{{ route('daily-reports.index') }}"
                   class="mt-3 inline-block text-xs font-semibold
                          text-blue-600 hover:text-blue-800">
                    View Reports →
                </a>

            </div>


            {{-- Active Maintenance Activities --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Active Work Activities
                        </p>

                        <h3 class="mt-3 text-3xl font-bold text-amber-600">
                            {{ number_format($activeMaintenance) }}
                        </h3>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center
                                rounded-xl bg-amber-50 text-amber-600">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M14.7 6.3a4 4 0 01-5.4 5.4L4 17v3h3l5.3-5.3
                                     a4 4 0 005.4-5.4l-2 2-2-2 2-2z"/>
                        </svg>

                    </div>

                </div>

                <p class="mt-4 text-xs text-slate-400">
                    Open and Progress activities
                </p>

                <a href="{{ route('daily-reports.index') }}"
                   class="mt-3 inline-block text-xs font-semibold
                          text-blue-600 hover:text-blue-800">
                    View Activities →
                </a>

            </div>


            {{-- Equipment --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Total Equipment
                        </p>

                        <h3 class="mt-3 text-3xl font-bold text-slate-800">
                            {{ number_format($totalEquipments) }}
                        </h3>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center
                                rounded-xl bg-blue-50 text-blue-600">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M10.3 4.3a2.3 2.3 0 013.4 0l1.1 1.1
                                     a2.3 2.3 0 010 3.4l-1 1 1.9 1.9
                                     1-1a2.3 2.3 0 013.4 0l1.1 1.1
                                     a2.3 2.3 0 010 3.4l-4.4 4.4
                                     a2.3 2.3 0 01-3.4 0l-1.1-1.1
                                     a2.3 2.3 0 010-3.4"/>
                        </svg>

                    </div>

                </div>

                <p class="mt-4 text-xs text-slate-400">
                    Registered equipment assets
                </p>

                <a href="{{ route('equipment.index') }}"
                   class="mt-3 inline-block text-xs font-semibold
                          text-blue-600 hover:text-blue-800">
                    View Equipment →
                </a>

            </div>


            {{-- Training --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Training Programs
                        </p>

                        <h3 class="mt-3 text-3xl font-bold text-slate-800">
                            {{ number_format($totalTrainings) }}
                        </h3>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center
                                rounded-xl bg-purple-50 text-purple-600">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 3 2 8l10 5 10-5-10-5Z
                                     M6 10v5c3.5 3 8.5 3 12 0v-5
                                     M22 8v6"/>
                        </svg>

                    </div>

                </div>

                <p class="mt-4 text-xs text-slate-400">
                    {{ $plannedTrainings }} planned ·
                    {{ $inProgressTrainings }} in progress
                </p>

                <a href="{{ route('trainings.index') }}"
                   class="mt-3 inline-block text-xs font-semibold
                          text-blue-600 hover:text-blue-800">
                    View Training →
                </a>

            </div>


            {{-- Competency Gap --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-start justify-between">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Competency Gaps
                        </p>

                        <h3 class="mt-3 text-3xl font-bold
                                   {{ $competencyGapCount > 0
                                        ? 'text-amber-600'
                                        : 'text-emerald-600' }}">
                            {{ number_format($competencyGapCount) }}
                        </h3>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center
                                rounded-xl bg-amber-50 text-amber-600">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 9v4m0 4h.01
                                     M10.3 3.9 2.6 17.2A2 2 0 004.3 20h15.4
                                     a2 2 0 001.7-2.8L13.7 3.9a2 2 0 00-3.4 0Z"/>
                        </svg>

                    </div>

                </div>

                <p class="mt-4 text-xs text-slate-400">
                    {{ $totalCompetencyGapLevels }} accumulated gap levels
                </p>

                <a href="{{ route('employee-competencies.index') }}"
                   class="mt-3 inline-block text-xs font-semibold
                          text-blue-600 hover:text-blue-800">
                    View Assessments →
                </a>

            </div>

        </div>

    </section>


    {{-- =====================================================
         DAILY REPORT AND MAINTENANCE OVERVIEW
    ====================================================== --}}

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">


        {{-- DAILY REPORT --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200
                    bg-white shadow-sm">

            <div class="flex items-center justify-between
                        border-b border-slate-200 px-6 py-5">

                <div>
                    <h2 class="font-bold text-slate-800">
                        Daily Report Overview
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        Current report publication status
                    </p>
                </div>

                <a href="{{ route('daily-reports.index') }}"
                   class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                    View All →
                </a>

            </div>

            <div class="space-y-5 p-6">

                <div class="grid grid-cols-2 gap-3">

                    @foreach($dailyReportStatusConfig as $status)
                        <div class="rounded-xl border border-slate-200 p-4">

                            <p class="text-sm text-slate-500">
                                {{ $status['label'] }} Reports
                            </p>

                            <p class="mt-2 text-2xl font-bold {{ $status['color'] }}">
                                {{ number_format($status['count']) }}
                            </p>

                        </div>
                    @endforeach

                </div>

                <div>

                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-700">
                            Publication Progress
                        </p>

                        <p class="text-sm font-bold text-emerald-600">
                            {{ round($reportCountWidth, 1) }}%
                        </p>
                    </div>

                    <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-100">

                        <div class="h-full rounded-full bg-emerald-500"
                             style="width: {{ $reportCountWidth }}%">
                        </div>

                    </div>

                    <p class="mt-2 text-xs text-slate-400">
                        {{ $publishedReports }} published from
                        {{ $totalReports }} total reports
                    </p>

                </div>

                <div class="border-t border-slate-100 pt-5">

                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-800">
                            Work Activity Status
                        </h3>

                        <span class="text-xs text-slate-400">
                            {{ $openDailyActivities + $progressDailyActivities + $completedDailyActivities }}
                            records
                        </span>
                    </div>

                    <div class="mt-3 grid grid-cols-3 gap-2">

                        @foreach($maintenanceActivityConfig as $activity)
                            <div class="rounded-xl p-3 {{ $activity['background'] }}">

                                <p class="text-xs text-slate-500">
                                    {{ $activity['label'] }}
                                </p>

                                <p class="mt-1 text-xl font-bold {{ $activity['color'] }}">
                                    {{ $activity['count'] }}
                                </p>

                            </div>
                        @endforeach

                    </div>

                </div>

            </div>

        </div>


        {{-- MAINTENANCE HISTORY --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200
                    bg-white shadow-sm">

            <div class="flex items-center justify-between
                        border-b border-slate-200 px-6 py-5">

                <div>
                    <h2 class="font-bold text-slate-800">
                        Maintenance History
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        Recorded equipment maintenance activities
                    </p>
                </div>

                <a href="{{ route('maintenance.index') }}"
                   class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                    View Maintenance →
                </a>

            </div>

            <div class="p-6">

                <div class="grid grid-cols-2 gap-3">

                    <div class="rounded-xl bg-blue-50 p-4">
                        <p class="text-xs text-blue-700">
                            Total Histories
                        </p>

                        <p class="mt-2 text-2xl font-bold text-blue-800">
                            {{ number_format($totalMaintenanceHistories) }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-amber-50 p-4">
                        <p class="text-xs text-amber-700">
                            Pending Results
                        </p>

                        <p class="mt-2 text-2xl font-bold text-amber-800">
                            {{ number_format($maintenancePendingResults) }}
                        </p>
                    </div>

                </div>

                <div class="mt-4 space-y-3">

                    @foreach([
                        ['label' => 'Open', 'count' => $maintenanceOpenCount, 'color' => 'bg-amber-500'],
                        ['label' => 'Progress', 'count' => $maintenanceProgressCount, 'color' => 'bg-blue-500'],
                        ['label' => 'Completed', 'count' => $maintenanceCompletedCount, 'color' => 'bg-emerald-500'],
                    ] as $status)

                        <div class="flex items-center justify-between">

                            <p class="text-sm text-slate-600">
                                {{ $status['label'] }}
                            </p>

                            <p class="text-sm font-semibold text-slate-800">
                                {{ $status['count'] }}
                            </p>

                        </div>

                    @endforeach

                </div>

                @if($maintenanceFailedResults > 0)
                    <div class="mt-5 rounded-xl border border-red-200
                                bg-red-50 p-3">

                        <p class="text-sm font-semibold text-red-700">
                            {{ $maintenanceFailedResults }}
                            Failed maintenance results
                        </p>

                        <p class="mt-1 text-xs text-red-600">
                            Perlu ditinjau oleh tim terkait.
                        </p>

                    </div>
                @endif

            </div>

        </div>

    </section>


    {{-- =====================================================
         EQUIPMENT OVERVIEW
    ====================================================== --}}

    <section class="overflow-hidden rounded-2xl border border-slate-200
                    bg-white shadow-sm">

        <div class="flex flex-col gap-3 border-b border-slate-200
                    px-6 py-5 sm:flex-row sm:items-center
                    sm:justify-between">

            <div>
                <h2 class="font-bold text-slate-800">
                    Equipment Status Overview
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Current condition of registered equipment assets.
                </p>
            </div>

            <a href="{{ route('equipment.index') }}"
               class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                Manage Equipment →
            </a>

        </div>

        <div class="p-6">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2
                        lg:grid-cols-5">

                @foreach($equipmentStatusConfig as $status)

                    @php
                        $equipmentPercentage = $totalEquipments > 0
                            ? ($status['count'] / $totalEquipments) * 100
                            : 0;
                    @endphp

                    <div class="rounded-xl border border-slate-200 p-4">

                        <div class="flex items-center justify-between gap-2">

                            <span class="text-sm text-slate-500">
                                {{ $status['label'] }}
                            </span>

                            <span class="h-2.5 w-2.5 shrink-0 rounded-full
                                         {{ $status['color'] }}">
                            </span>

                        </div>

                        <p class="mt-3 text-2xl font-bold text-slate-800">
                            {{ number_format($status['count']) }}
                        </p>

                        <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-slate-100">

                            <div class="h-full rounded-full {{ $status['color'] }}"
                                 style="width: {{ $equipmentPercentage }}%">
                            </div>

                        </div>

                        <p class="mt-2 text-xs text-slate-400">
                            {{ number_format($equipmentPercentage, 1) }}%
                            of total
                        </p>

                    </div>

                @endforeach

            </div>

        </div>

    </section>


    {{-- =====================================================
         TRAINING OVERVIEW
    ====================================================== --}}

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- Training Summary --}}
        <div class="xl:col-span-1 overflow-hidden rounded-2xl
                    border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="font-bold text-slate-800">
                    Training Overview
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Training program distribution
                </p>
            </div>

            <div class="space-y-4 p-6">

                @foreach([
                    ['label' => 'Planned', 'count' => $plannedTrainings, 'color' => 'text-blue-600'],
                    ['label' => 'In Progress', 'count' => $inProgressTrainings, 'color' => 'text-amber-600'],
                    ['label' => 'Completed', 'count' => $completedTrainings, 'color' => 'text-emerald-600'],
                    ['label' => 'Cancelled', 'count' => $cancelledTrainings, 'color' => 'text-slate-500'],
                ] as $trainingStatus)

                    <div class="flex items-center justify-between">

                        <p class="text-sm text-slate-600">
                            {{ $trainingStatus['label'] }}
                        </p>

                        <p class="text-sm font-bold {{ $trainingStatus['color'] }}">
                            {{ number_format($trainingStatus['count']) }}
                        </p>

                    </div>

                @endforeach

                <div class="border-t border-slate-100 pt-4">

                    <div class="flex items-center justify-between">
                        <p class="text-sm text-slate-600">
                            Pending Registrations
                        </p>

                        <p class="font-bold text-amber-600">
                            {{ $pendingTrainingRegistrations }}
                        </p>
                    </div>

                    <div class="mt-3 flex items-center justify-between">
                        <p class="text-sm text-slate-600">
                            Approved Registrations
                        </p>

                        <p class="font-bold text-emerald-600">
                            {{ $approvedTrainingRegistrations }}
                        </p>
                    </div>

                    <div class="mt-3 flex items-center justify-between">
                        <p class="text-sm text-slate-600">
                            Present Participants
                        </p>

                        <p class="font-bold text-blue-600">
                            {{ $presentTrainingParticipants }}
                        </p>
                    </div>

                </div>

            </div>

            <div class="border-t border-slate-100 p-5">
                <a href="{{ route('trainings.index') }}"
                   class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                    Open Training Management →
                </a>
            </div>

        </div>


        {{-- Upcoming Training --}}
        <div class="xl:col-span-2 overflow-hidden rounded-2xl
                    border border-slate-200 bg-white shadow-sm">

            <div class="flex items-center justify-between
                        border-b border-slate-200 px-6 py-5">

                <div>
                    <h2 class="font-bold text-slate-800">
                        Upcoming Training
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        Scheduled training programs
                    </p>
                </div>

                <a href="{{ route('trainings.index') }}"
                   class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                    View All →
                </a>

            </div>

            <div class="divide-y divide-slate-100">

                @forelse($upcomingTrainings as $training)

                    <div class="flex flex-col gap-3 p-5 sm:flex-row
                                sm:items-center sm:justify-between">

                        <div class="min-w-0">

                            <a href="{{ route('trainings.show', $training) }}"
                               class="font-semibold text-slate-800
                                      transition hover:text-blue-600">
                                {{ $training->title }}
                            </a>

                            <p class="mt-1 text-xs text-slate-500">
                                {{ $training->organizer ?: 'Organizer not specified' }}
                            </p>

                            <p class="mt-2 text-xs text-slate-500">
                                {{ $training->start_date->format('d M Y') }}
                                –
                                {{ $training->end_date->format('d M Y') }}
                            </p>

                        </div>

                        <div class="flex items-center gap-3 sm:shrink-0">

                            <div>
                                <p class="text-sm font-bold text-slate-800">
                                    {{ $training->active_participants_count }}
                                    / {{ $training->quota ?? '∞' }}
                                </p>

                                <p class="text-xs text-slate-400">
                                    Registrations
                                </p>
                            </div>

                            <span class="rounded-full bg-blue-50 px-3 py-1
                                         text-xs font-semibold text-blue-700">
                                Planned
                            </span>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-12 text-center">
                        <p class="font-semibold text-slate-800">
                            No Upcoming Training
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            Belum ada program pelatihan terjadwal.
                        </p>
                    </div>

                @endforelse

            </div>

        </div>

    </section>


    {{-- =====================================================
         COMPETENCY DEVELOPMENT
    ====================================================== --}}

    <section class="overflow-hidden rounded-2xl border border-slate-200
                    bg-white shadow-sm">

        <div class="flex flex-col gap-3 border-b border-slate-200
                    px-6 py-5 sm:flex-row sm:items-center
                    sm:justify-between">

            <div>
                <h2 class="font-bold text-slate-800">
                    Competency Development
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Assessment progress and competency gap monitoring.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">

                @if($isAdmin)
                    <a href="{{ route('competencies.dashboard') }}"
                       class="rounded-xl border border-slate-300 px-4 py-2
                              text-xs font-semibold text-slate-700
                              hover:bg-slate-50">
                        Gap Analysis
                    </a>
                @endif

                <a href="{{ route('training-recommendations.index') }}"
                   class="rounded-xl bg-blue-600 px-4 py-2
                          text-xs font-semibold text-white
                          hover:bg-blue-700">
                    Training Recommendations
                </a>

            </div>

        </div>

        <div class="grid grid-cols-1 gap-6 p-6 lg:grid-cols-2">

            {{-- Assessment Coverage --}}
            <div>

                <div class="flex items-center justify-between gap-3">

                    <div>
                        <p class="text-sm font-semibold text-slate-800">
                            Assessment Coverage
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Competency records with actual assessment
                        </p>
                    </div>

                    <p class="text-2xl font-bold text-blue-600">
                        {{ $competencyAssessmentRate }}%
                    </p>

                </div>

                <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-100">

                    <div class="h-full rounded-full bg-blue-600
                                transition-all duration-500"
                         style="width: {{ $competencyCoverageWidth }}%">
                    </div>

                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-xs text-slate-500">
                            Assessed
                        </p>

                        <p class="mt-1 text-xl font-bold text-slate-800">
                            {{ $assessedCompetencies }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-xs text-slate-500">
                            Not Assessed
                        </p>

                        <p class="mt-1 text-xl font-bold text-slate-800">
                            {{ $notAssessedCompetencies }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- Gap Summary --}}
            <div>

                <p class="text-sm font-semibold text-slate-800">
                    Competency Gap Summary
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Current assessment compared with target level.
                </p>

                <div class="mt-4 grid grid-cols-2 gap-3">

                    <div class="rounded-xl bg-amber-50 p-4">
                        <p class="text-xs text-amber-700">
                            Below Target
                        </p>

                        <p class="mt-1 text-2xl font-bold text-amber-700">
                            {{ $competencyGapCount }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-emerald-50 p-4">
                        <p class="text-xs text-emerald-700">
                            Target Met
                        </p>

                        <p class="mt-1 text-2xl font-bold text-emerald-700">
                            {{ $targetMetCompetencies }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-xs text-slate-500">
                            Total Gap Levels
                        </p>

                        <p class="mt-1 text-2xl font-bold text-slate-800">
                            {{ $totalCompetencyGapLevels }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-blue-50 p-4">
                        <p class="text-xs text-blue-700">
                            Recommended Programs
                        </p>

                        <p class="mt-1 text-2xl font-bold text-blue-700">
                            {{ $recommendationCount }}
                        </p>
                    </div>

                </div>

                <p class="mt-3 text-xs text-slate-400">
                    {{ $competencyTargetMetRate }}% of assessed records
                    have reached their target.
                </p>

            </div>

        </div>

    </section>


    {{-- =====================================================
         DEVELOPMENT PRIORITY
    ====================================================== --}}

    <section class="overflow-hidden rounded-2xl border border-slate-200
                    bg-white shadow-sm">

        <div class="flex items-center justify-between gap-3
                    border-b border-slate-200 px-6 py-5">

            <div>
                <h2 class="font-bold text-slate-800">
                    Development Priority
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Competencies with the largest level gap.
                </p>
            </div>

            <a href="{{ route('employee-competencies.index') }}"
               class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                Manage →
            </a>

        </div>

        @if($priorityCompetencies->isEmpty())

            <div class="px-6 py-12 text-center">
                <p class="font-semibold text-slate-800">
                    No Development Priority
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Belum ada kompetensi yang berada di bawah target.
                </p>
            </div>

        @else

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">
                        <tr>

                            @if($isAdmin)
                                <th class="px-5 py-3 text-left text-xs
                                           font-semibold uppercase
                                           tracking-wider text-slate-500">
                                    Employee
                                </th>
                            @endif

                            <th class="px-5 py-3 text-left text-xs
                                       font-semibold uppercase
                                       tracking-wider text-slate-500">
                                Competency
                            </th>

                            <th class="px-5 py-3 text-left text-xs
                                       font-semibold uppercase
                                       tracking-wider text-slate-500">
                                Current
                            </th>

                            <th class="px-5 py-3 text-left text-xs
                                       font-semibold uppercase
                                       tracking-wider text-slate-500">
                                Target
                            </th>

                            <th class="px-5 py-3 text-left text-xs
                                       font-semibold uppercase
                                       tracking-wider text-slate-500">
                                Gap
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @foreach($priorityCompetencies as $record)

                            @php
                                $gap = $record->target_level
                                    - $record->current_level;
                            @endphp

                            <tr class="hover:bg-slate-50/70">

                                @if($isAdmin)
                                    <td class="px-5 py-4">

                                        <p class="text-sm font-semibold text-slate-800">
                                            {{ $record->employee?->name ?? 'Unknown' }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $record->employee?->nik ?? '-' }}
                                        </p>

                                    </td>
                                @endif

                                <td class="px-5 py-4">

                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $record->competency?->name ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $record->competency?->code ?? '-' }}
                                    </p>

                                </td>

                                <td class="whitespace-nowrap px-5 py-4
                                           text-sm text-slate-600">
                                    Level {{ $record->current_level }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4
                                           text-sm text-slate-600">
                                    Level {{ $record->target_level }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">

                                    <span class="rounded-full bg-amber-50
                                                 px-3 py-1 text-xs font-bold
                                                 text-amber-700">
                                        Gap {{ $gap }}
                                    </span>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </section>


    {{-- =====================================================
         DAILY REPORT TREND
    ====================================================== --}}

    <section class="rounded-2xl border border-slate-200
                    bg-white p-6 shadow-sm">

        <div>
            <h2 class="font-bold text-slate-800">
                Daily Report Trend
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                Report activity over the last six months.
            </p>
        </div>

        <div class="mt-6 grid grid-cols-6 items-end gap-3">

            @foreach($reportTrend as $month)

                @php
                    $barHeight = $month['total'] > 0
                        ? max(8, ($month['total'] / $maxMonthlyReports) * 100)
                        : 0;
                @endphp

                <div class="flex min-w-0 flex-col items-center">

                    <p class="mb-2 text-xs font-semibold text-slate-700">
                        {{ $month['total'] }}
                    </p>

                    <div class="flex h-32 w-full items-end justify-center
                                overflow-hidden rounded-t-lg bg-slate-50">

                        @if($month['total'] > 0)
                            <div
                                class="w-full max-w-12 rounded-t-lg bg-blue-600
                                       transition-all duration-500"
                                style="height: {{ $barHeight }}%"
                            ></div>
                        @endif

                    </div>

                    <p class="mt-2 text-center text-xs text-slate-500">
                        {{ $month['label'] }}
                    </p>

                </div>

            @endforeach

        </div>

    </section>


    {{-- =====================================================
         RECENT REPORTS AND MAINTENANCE
    ====================================================== --}}

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">


        {{-- RECENT DAILY REPORTS --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200
                    bg-white shadow-sm">

            <div class="flex items-center justify-between
                        border-b border-slate-200 px-6 py-5">

                <div>
                    <h2 class="font-bold text-slate-800">
                        Recent Daily Reports
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        Latest submitted reports
                    </p>
                </div>

                <a href="{{ route('daily-reports.index') }}"
                   class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                    View All →
                </a>

            </div>

            <div class="divide-y divide-slate-100">

                @forelse($recentReports as $report)

                    <div class="flex items-start justify-between gap-3 p-5">

                        <div class="min-w-0">

                            <p class="truncate text-sm font-semibold text-slate-800">
                                {{ $report->report_number }}
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                {{ $report->report_date->format('d M Y') }}
                                <span class="mx-1">·</span>
                                {{ $report->shift }}
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                {{ $report->creator?->name ?? 'Unknown' }}
                                <span class="mx-1">·</span>
                                {{ $report->items_count }} activities
                            </p>

                        </div>

                        <span class="shrink-0 rounded-full px-3 py-1
                                     text-xs font-semibold
                            {{ $report->status === 'Published'
                                ? 'bg-emerald-50 text-emerald-700'
                                : 'bg-slate-100 text-slate-600' }}">
                            {{ $report->status }}
                        </span>

                    </div>

                @empty

                    <div class="px-6 py-12 text-center text-sm text-slate-500">
                        No daily reports available.
                    </div>

                @endforelse

            </div>

        </div>


        {{-- RECENT MAINTENANCE HISTORY --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200
                    bg-white shadow-sm">

            <div class="flex items-center justify-between
                        border-b border-slate-200 px-6 py-5">

                <div>
                    <h2 class="font-bold text-slate-800">
                        Recent Maintenance History
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        Latest equipment maintenance records
                    </p>
                </div>

                <a href="{{ route('maintenance.index') }}"
                   class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                    View All →
                </a>

            </div>

            <div class="divide-y divide-slate-100">

                @forelse($recentMaintenanceHistories as $history)

                    <div class="flex items-start justify-between gap-3 p-5">

                        <div class="min-w-0">

                            <p class="truncate text-sm font-semibold text-slate-800">
                                {{ $history->equipment?->equipment_name ?? 'Unknown Equipment' }}
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                {{ $history->equipment?->equipment_code ?? '-' }}
                                <span class="mx-1">·</span>
                                {{ $history->maintenance_type }}
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                {{ $history->maintenance_date?->format('d M Y') ?? '-' }}
                                <span class="mx-1">·</span>
                                {{ $history->technician ?: 'Technician not specified' }}
                            </p>

                        </div>

                        <span class="shrink-0 rounded-full px-3 py-1
                                     text-xs font-semibold
                            {{ $history->result === 'Completed'
                                ? 'bg-emerald-50 text-emerald-700'
                                : ($history->result === 'Failed'
                                    ? 'bg-red-50 text-red-700'
                                    : 'bg-amber-50 text-amber-700') }}">
                            {{ $history->result }}
                        </span>

                    </div>

                @empty

                    <div class="px-6 py-12 text-center text-sm text-slate-500">
                        No maintenance history available.
                    </div>

                @endforelse

            </div>

        </div>

    </section>


    {{-- =====================================================
         RECENT WORK ACTIVITIES
    ====================================================== --}}

    <section class="overflow-hidden rounded-2xl border border-slate-200
                    bg-white shadow-sm">

        <div class="flex items-center justify-between
                    border-b border-slate-200 px-6 py-5">

            <div>
                <h2 class="font-bold text-slate-800">
                    Recent Daily Report Activities
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Recent work activities from department reports.
                </p>
            </div>

            <a href="{{ route('daily-reports.index') }}"
               class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                View Reports →
            </a>

        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                    <tr>
                        <th class="px-5 py-3 text-left text-xs
                                   font-semibold uppercase text-slate-500">
                            Activity
                        </th>

                        <th class="px-5 py-3 text-left text-xs
                                   font-semibold uppercase text-slate-500">
                            Category
                        </th>

                        <th class="px-5 py-3 text-left text-xs
                                   font-semibold uppercase text-slate-500">
                            Report
                        </th>

                        <th class="px-5 py-3 text-left text-xs
                                   font-semibold uppercase text-slate-500">
                            Workers
                        </th>

                        <th class="px-5 py-3 text-left text-xs
                                   font-semibold uppercase text-slate-500">
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($recentReportActivities as $item)

                        <tr class="hover:bg-slate-50/70">

                            <td class="px-5 py-4">

                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $item->title }}
                                </p>

                                <p class="mt-1 max-w-xs truncate text-xs text-slate-400">
                                    {{ $item->description }}
                                </p>

                            </td>

                            <td class="whitespace-nowrap px-5 py-4
                                       text-xs text-slate-600">
                                {{ $item->category }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">

                                <p class="text-xs font-medium text-slate-700">
                                    {{ $item->dailyReport?->report_number ?? '-' }}
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $item->dailyReport?->report_date?->format('d M Y') ?? '-' }}
                                </p>

                            </td>

                            <td class="whitespace-nowrap px-5 py-4
                                       text-xs text-slate-600">
                                {{ $item->workers->count() }}
                                workers
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">

                                <span class="rounded-full px-3 py-1
                                             text-xs font-semibold
                                    {{ $item->status === 'Done'
                                        ? 'bg-emerald-50 text-emerald-700'
                                        : ($item->status === 'Progress'
                                            ? 'bg-amber-50 text-amber-700'
                                            : 'bg-red-50 text-red-700') }}">
                                    {{ $item->status }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5"
                                class="px-6 py-12 text-center text-sm text-slate-500">
                                No recent work activities.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>


    {{-- =====================================================
         QUICK ACCESS
    ====================================================== --}}

    <section>

        <div class="mb-4">
            <h2 class="text-lg font-bold text-slate-800">
                Quick Access
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Quickly navigate to DRICS modules.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

            {{-- Equipment --}}
            <a href="{{ route('equipment.index') }}"
               class="group rounded-2xl border border-slate-200
                      bg-white p-5 shadow-sm transition
                      hover:border-blue-200 hover:shadow-md">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 items-center
                                justify-center rounded-xl bg-blue-50
                                text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M10.3 4.3a2.3 2.3 0 013.4 0l1.1 1.1
                                     a2.3 2.3 0 010 3.4l-1 1 1.9 1.9"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="m8.2 11.8-1 1a2.3 2.3 0 010 3.4l1.1 1.1
                                     a2.3 2.3 0 003.4 0l4.4-4.4"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="font-semibold text-slate-800
                                   group-hover:text-blue-600">
                            Equipment
                        </h3>

                        <p class="mt-1 text-xs text-slate-500">
                            Manage equipment master
                        </p>
                    </div>

                </div>

            </a>


            {{-- Daily Reports --}}
            <a href="{{ route('daily-reports.index') }}"
               class="group rounded-2xl border border-slate-200
                      bg-white p-5 shadow-sm transition
                      hover:border-blue-200 hover:shadow-md">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 items-center
                                justify-center rounded-xl bg-indigo-50
                                text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M6 3h9l3 3v15H6a2 2 0 01-2-2V5a2 2 0 012-2z
                                     M14 3v4h4M8 11h8M8 15h8M8 19h5"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="font-semibold text-slate-800
                                   group-hover:text-indigo-600">
                            Daily Reports
                        </h3>

                        <p class="mt-1 text-xs text-slate-500">
                            Review department reports
                        </p>
                    </div>

                </div>

            </a>


            {{-- Maintenance --}}
            <a href="{{ route('maintenance.index') }}"
               class="group rounded-2xl border border-slate-200
                      bg-white p-5 shadow-sm transition
                      hover:border-amber-200 hover:shadow-md">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 items-center
                                justify-center rounded-xl bg-amber-50
                                text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M14.7 6.3a4 4 0 01-5.4 5.4L4 17v3h3l5.3-5.3
                                     a4 4 0 005.4-5.4l-2 2-2-2 2-2z"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="font-semibold text-slate-800
                                   group-hover:text-amber-600">
                            Maintenance
                        </h3>

                        <p class="mt-1 text-xs text-slate-500">
                            Monitor maintenance history
                        </p>
                    </div>

                </div>

            </a>


            {{-- Training --}}
            <a href="{{ route('trainings.index') }}"
               class="group rounded-2xl border border-slate-200
                      bg-white p-5 shadow-sm transition
                      hover:border-purple-200 hover:shadow-md">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 items-center
                                justify-center rounded-xl bg-purple-50
                                text-purple-600">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 3 2 8l10 5 10-5-10-5Z
                                     M6 10v5c3.5 3 8.5 3 12 0v-5
                                     M22 8v6"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="font-semibold text-slate-800
                                   group-hover:text-purple-600">
                            Training
                        </h3>

                        <p class="mt-1 text-xs text-slate-500">
                            Manage training programs
                        </p>
                    </div>

                </div>

            </a>


            {{-- Competency --}}
            <a href="{{ route('competencies.index') }}"
               class="group rounded-2xl border border-slate-200
                      bg-white p-5 shadow-sm transition
                      hover:border-blue-200 hover:shadow-md">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 items-center
                                justify-center rounded-xl bg-blue-50
                                text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="m12 3 9 3.5v5c0 5-4 8-9 9.5
                                     -5-1.5-9-4.5-9-9.5v-5L12 3Z"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="m8 12 2.5 2.5L16 9"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="font-semibold text-slate-800
                                   group-hover:text-blue-600">
                            Competency
                        </h3>

                        <p class="mt-1 text-xs text-slate-500">
                            Manage employee competencies
                        </p>
                    </div>

                </div>

            </a>


            {{-- Training Recommendations --}}
            <a href="{{ route('training-recommendations.index') }}"
               class="group rounded-2xl border border-slate-200
                      bg-white p-5 shadow-sm transition
                      hover:border-emerald-200 hover:shadow-md">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 items-center
                                justify-center rounded-xl bg-emerald-50
                                text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 6v6l4 2"/>
                            <circle cx="12" cy="12" r="9"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="font-semibold text-slate-800
                                   group-hover:text-emerald-600">
                            Training Recommendations
                        </h3>

                        <p class="mt-1 text-xs text-slate-500">
                            Review competency-based training
                        </p>
                    </div>

                </div>

            </a>

        </div>

    </section>


    {{-- =====================================================
         ADMIN ALERTS
    ====================================================== --}}

    @if($isAdmin)

        <section class="rounded-2xl border border-slate-200
                        bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between gap-3">

                <div>
                    <h2 class="font-bold text-slate-800">
                        Administration
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        Items requiring administrative attention.
                    </p>
                </div>

                <a href="{{ route('admin.user-approvals.index') }}"
                   class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                    User Approvals →
                </a>

            </div>

            <div class="mt-4 flex items-center justify-between
                        rounded-xl bg-slate-50 p-4">

                <div>
                    <p class="text-sm font-semibold text-slate-800">
                        Pending Employee Accounts
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        User accounts waiting for approval.
                    </p>
                </div>

                <span class="text-2xl font-bold
                             {{ $pendingEmployees > 0
                                ? 'text-amber-600'
                                : 'text-emerald-600' }}">
                    {{ $pendingEmployees }}
                </span>

            </div>

        </section>

    @endif


</div>

@endsection
