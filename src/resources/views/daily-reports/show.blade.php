@extends('layouts.app')

@section('title', 'Daily Report Detail')
@section('header', 'Daily Report Detail')

@section('content')

@php
    $isDraft = $dailyReport->status === 'Draft';

    $reportStatus = match ($dailyReport->status) {
        'Draft' => [
            'label' => 'Draft',
            'class' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
            'dot' => 'bg-amber-500',
        ],
        'Published' => [
            'label' => 'Published',
            'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
            'dot' => 'bg-emerald-500',
        ],
        default => [
            'label' => $dailyReport->status,
            'class' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
            'dot' => 'bg-slate-400',
        ],
    };

    $oldWorkers = old('workers', []);

    if (!is_array($oldWorkers)) {
        $oldWorkers = [];
    }
@endphp

<div class="mx-auto max-w-7xl space-y-6">

    {{-- =====================================================
         FLASH MESSAGES
    ====================================================== --}}

    @if (session('success'))
        <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700">
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="m5 12 4 4L19 6"/>
            </svg>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v4m0 4h.01M10.3 3.9 2.7 17.1A2 2 0 0 0 4.4 20h15.2a2 2 0 0 0 1.7-2.9L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
            </svg>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-5">
            <h3 class="text-sm font-semibold text-red-800">
                Ada data yang perlu diperbaiki
            </h3>

            <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <a href="{{ route('daily-reports.index') }}"
                    class="transition hover:text-blue-600">
                    Daily Reports
                </a>

                <span>/</span>
                <span class="text-slate-700">Detail</span>
            </div>

            <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-800">
                Daily Report Detail
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Informasi laporan dan aktivitas pekerjaan harian Instrument & Control System.
            </p>
        </div>

        <a
            href="{{ route('daily-reports.index') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="1.8" d="m15 18-6-6 6-6"/>
            </svg>
            Back to Reports
        </a>

    </div>

    {{-- =====================================================
         REPORT INFORMATION
    ====================================================== --}}

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Report Header --}}
        <div class="flex flex-col gap-5 border-b border-slate-200 p-5 sm:p-6 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <div class="flex flex-wrap items-center gap-3">

                    <h2 class="text-xl font-bold tracking-tight text-slate-800">
                        {{ $dailyReport->report_number }}
                    </h2>

                    <span class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold ring-1 ring-inset {{ $reportStatus['class'] }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $reportStatus['dot'] }}"></span>
                        {{ $reportStatus['label'] }}
                    </span>

                </div>

                <p class="mt-2 text-sm text-slate-500">
                    Daily Operational Report
                </p>
            </div>

            {{-- Report Actions --}}
            <div class="flex flex-wrap items-center gap-2">

                {{-- Preview PDF: Available for Draft and Published --}}
                <a
                    href="{{ route('daily-reports.preview', $dailyReport) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700 transition hover:bg-blue-100"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M2.25 12s3.5-6 9.75-6 9.75 6 9.75 6-3.5 6-9.75 6-9.75-6-9.75-6Z"/>
                        <circle cx="12" cy="12" r="3"
                            stroke-width="1.8"/>
                    </svg>
                    Preview PDF
                </a>

                @if ($isDraft)

                    {{-- Edit --}}
                    <a
                        href="{{ route('daily-reports.edit', $dailyReport) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                    >
                        <svg class="h-4 w-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="1.8"
                                d="m16.5 3.5 4 4M4 20l4.5-1 11-11a2.12 2.12 0 0 0-3-3l-11 11L4 20Z"/>
                        </svg>
                        Edit
                    </a>

                    {{-- Publish --}}
                    <form
                        method="POST"
                        action="{{ route('daily-reports.publish', $dailyReport) }}"
                        onsubmit="return confirm('Publish Daily Report ini? Setelah dipublikasikan, data tidak dapat diedit.')"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            <svg class="h-4 w-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M5 12.5 9.5 17 19 7.5"/>
                            </svg>
                            Publish
                        </button>
                    </form>

                @endif

            </div>

        </div>

        {{-- Report Metadata --}}
        <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6 lg:grid-cols-4">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Report Date
                </p>
                <p class="mt-2 text-sm font-semibold text-slate-800">
                    {{ $dailyReport->report_date?->format('d M Y') ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Shift
                </p>
                <p class="mt-2 text-sm font-semibold text-slate-800">
                    {{ $dailyReport->shift ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Division
                </p>
                <p class="mt-2 text-sm font-semibold text-slate-800">
                    {{ $dailyReport->division ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Created By
                </p>
                <p class="mt-2 text-sm font-semibold text-slate-800">
                    {{ $dailyReport->creator?->name ?? '-' }}
                </p>
            </div>

            @if ($dailyReport->shift === 'Piket Malam')
                <div class="sm:col-span-2 lg:col-span-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Night Duty Officer
                    </p>
                    <p class="mt-2 text-sm font-semibold text-slate-800">
                        {{ $dailyReport->nightDutyUser?->name ?? '-' }}
                    </p>
                </div>
            @endif

        </div>

    </section>

    {{-- =====================================================
         EMPLOYEE ATTENDANCE
    ====================================================== --}}

    @include('daily-reports.partials.attendance', [
        'dailyReport' => $dailyReport,
        'workers' => $workers,
    ])

    {{-- =====================================================
         WORK ACTIVITIES
    ====================================================== --}}

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Section Header --}}
        <div class="flex flex-col gap-4 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">

            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    Work Activities
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Daftar aktivitas dan pekerjaan dalam laporan ini.
                </p>
            </div>

            @if ($isDraft)
                <button
                    type="button"
                    onclick="document.getElementById('activity-form').classList.toggle('hidden')"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                >
                    <svg class="h-4 w-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Activity
                </button>
            @endif

        </div>

        {{-- =====================================================
             ADD ACTIVITY FORM
        ====================================================== --}}

        @if ($isDraft)

            <div
                id="activity-form"
                class="{{ $errors->any() ? '' : 'hidden' }} border-b border-slate-200 bg-slate-50 p-5 sm:p-6"
            >

                <div class="mb-5">
                    <h3 class="font-semibold text-slate-800">
                        New Work Activity
                    </h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Isi informasi pekerjaan dan employee yang terlibat.
                    </p>
                </div>

                <form
                    method="POST"
                    action="{{ route('daily-reports.items.store', $dailyReport) }}"
                    class="space-y-5"
                >
                    @csrf

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        {{-- Category --}}
                        <div>
                            <label for="activity_category"
                                class="mb-2 block text-sm font-medium text-slate-700">
                                Category <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="activity_category"
                                name="category"
                                required
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            >
                                <option value="">Select Category</option>

                                @foreach ([
                                    'Perintah Atasan',
                                    'Pekerjaan Rutin',
                                    'Buku Cacat',
                                    'Preventive',
                                    'Corrective',
                                    'Emergency',
                                ] as $category)
                                    <option
                                        value="{{ $category }}"
                                        @selected(old('category') === $category)
                                    >
                                        {{ $category }}
                                    </option>
                                @endforeach

                            </select>

                            @error('category')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- SPK --}}
                        <div>
                            <label for="activity_spk"
                                class="mb-2 block text-sm font-medium text-slate-700">
                                SPK Number
                            </label>

                            <input
                                id="activity_spk"
                                type="text"
                                name="spk_number"
                                value="{{ old('spk_number') }}"
                                placeholder="Optional"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            >

                            @error('spk_number')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Title --}}
                        <div class="md:col-span-2">
                            <label for="activity_title"
                                class="mb-2 block text-sm font-medium text-slate-700">
                                Work Title <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="activity_title"
                                type="text"
                                name="title"
                                value="{{ old('title') }}"
                                placeholder="Contoh: Kalibrasi Flowmeter FT-201"
                                required
                                maxlength="255"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            >

                            @error('title')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-2">
                            <label for="activity_description"
                                class="mb-2 block text-sm font-medium text-slate-700">
                                Work Description <span class="text-red-500">*</span>
                            </label>

                            <textarea
                                id="activity_description"
                                name="description"
                                rows="4"
                                required
                                placeholder="Jelaskan aktivitas, kondisi equipment, tindakan, dan hasil pekerjaan..."
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="activity_status"
                                class="mb-2 block text-sm font-medium text-slate-700">
                                Activity Status <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="activity_status"
                                name="status"
                                required
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            >
                                @foreach (['Open', 'Progress', 'Done'] as $status)
                                    <option
                                        value="{{ $status }}"
                                        @selected(old('status', 'Open') === $status)
                                    >
                                        {{ $status === 'Progress' ? 'In Progress' : $status }}
                                    </option>
                                @endforeach
                            </select>

                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Workers --}}
                        <div>
                            <label for="activity_workers"
                                class="mb-2 block text-sm font-medium text-slate-700">
                                Assigned Workers
                            </label>

                            <select
                                id="activity_workers"
                                name="workers[]"
                                multiple
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            >
                                @foreach ($workers as $worker)
                                    <option
                                        value="{{ $worker->id }}"
                                        @selected(in_array($worker->id, $oldWorkers))
                                    >
                                        {{ $worker->name }}
                                        @if ($worker->nik)
                                            — {{ $worker->nik }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            <p class="mt-1 text-xs text-slate-500">
                                Gunakan Ctrl (Windows) atau Command (Mac) untuk memilih beberapa employee.
                            </p>

                            @error('workers')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror

                            @error('workers.*')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Form Actions --}}
                    <div class="flex flex-col-reverse gap-2 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">

                        <button
                            type="button"
                            onclick="document.getElementById('activity-form').classList.add('hidden')"
                            class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                        >
                            Save Activity
                        </button>

                    </div>

                </form>

            </div>

        @endif

        {{-- =====================================================
             ACTIVITY LIST
        ====================================================== --}}

        <div class="divide-y divide-slate-100">

            @forelse ($dailyReport->items as $item)

                @php
                    $activityStatus = match ($item->status) {
                        'Open' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                        'Progress' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                        'Done' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                        default => 'bg-slate-100 text-slate-600 ring-slate-500/20',
                    };
                @endphp

                <article class="p-5 transition hover:bg-slate-50/50 sm:p-6">

                    {{-- Activity Header --}}
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                        <div class="min-w-0">

                            <div class="flex flex-wrap items-center gap-2">

                                <span class="inline-flex rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                    {{ $item->category }}
                                </span>

                                <span class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $activityStatus }}">
                                    <span class="h-1.5 w-1.5 rounded-full
                                        {{ $item->status === 'Open' ? 'bg-amber-500' : '' }}
                                        {{ $item->status === 'Progress' ? 'bg-blue-500' : '' }}
                                        {{ $item->status === 'Done' ? 'bg-emerald-500' : '' }}">
                                    </span>

                                    {{ $item->status === 'Progress' ? 'In Progress' : $item->status }}
                                </span>

                            </div>

                            <h3 class="mt-3 break-words text-lg font-bold text-slate-800">
                                {{ $item->title }}
                            </h3>

                        </div>

                        {{-- Activity Actions --}}
                        @if ($isDraft)

                            <div class="flex shrink-0 items-center gap-2">

                                <a
                                    href="{{ route('daily-reports.items.edit', [$dailyReport, $item]) }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                                >
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('daily-reports.items.destroy', [$dailyReport, $item]) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus aktivitas ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                    >
                                        Delete
                                    </button>
                                </form>

                            </div>

                        @endif

                    </div>

                    {{-- Description --}}
                    <div class="mt-4">
                        <p class="whitespace-pre-line break-words text-sm leading-6 text-slate-600">
                            {{ $item->description }}
                        </p>
                    </div>

                    {{-- Activity Metadata --}}
                    <div class="mt-5 grid grid-cols-1 gap-4 border-t border-slate-100 pt-4 sm:grid-cols-2">

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                SPK Number
                            </p>

                            <p class="mt-1 text-sm font-medium text-slate-700">
                                {{ $item->spk_number ?: '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Assigned Workers
                            </p>

                            <div class="mt-1 flex flex-wrap gap-1.5">

                                @forelse ($item->workers as $worker)
                                    <span class="inline-flex rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">
                                        {{ $worker->name }}
                                    </span>
                                @empty
                                    <span class="text-sm text-slate-400">
                                        No workers assigned
                                    </span>
                                @endforelse

                            </div>
                        </div>

                    </div>

                </article>

            @empty

                <div class="px-6 py-16 text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                        <svg class="h-7 w-7" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M9 5h6m-8 4h10m-10 4h10m-10 4h6"/>
                        </svg>
                    </div>

                    <h3 class="mt-4 text-sm font-semibold text-slate-800">
                        No Work Activities
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Belum ada aktivitas pekerjaan pada Daily Report ini.
                    </p>

                    @if ($isDraft)
                        <button
                            type="button"
                            onclick="document.getElementById('activity-form').classList.remove('hidden')"
                            class="mt-4 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                        >
                            + Add First Activity
                        </button>
                    @endif

                </div>

            @endforelse

        </div>

    </section>

    {{-- =====================================================
         PAGE FOOTER
    ====================================================== --}}

    <div class="flex flex-col gap-2 border-t border-slate-200 pt-4 text-xs text-slate-400 sm:flex-row sm:items-center sm:justify-between">

        <p>
            DRICS — Digital Reliability &amp; Instrument Control System
        </p>

        <p>
            Report: {{ $dailyReport->report_number }}
        </p>

    </div>

</div>

@endsection
