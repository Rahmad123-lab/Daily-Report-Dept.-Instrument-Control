
@extends('layouts.app')

@section('title', 'Maintenance PDF Preview')
@section('header', 'Maintenance PDF Preview')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                Maintenance Report Preview
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Review maintenance report before downloading the PDF.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a
                href="{{ route('maintenance.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                Back to Maintenance
            </a>

            <a
                href="{{ route('maintenance-history.export.pdf', [$equipment, $history]) }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M12 3v12m0 0 4-4m-4 4-4-4M5 17v3h14v-3"/>
                </svg>
                Download PDF
            </a>
        </div>
    </div>

    {{-- Report Information --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    Report Information
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Maintenance record summary.
                </p>
            </div>

            @php
                $statusClass = match ($history->status) {
                    'Open' => 'bg-amber-50 text-amber-700',
                    'Progress' => 'bg-blue-50 text-blue-700',
                    'Completed' => 'bg-emerald-50 text-emerald-700',
                    default => 'bg-slate-100 text-slate-600',
                };
            @endphp

            <span class="inline-flex rounded-lg px-3 py-1.5 text-xs font-semibold {{ $statusClass }}">
                {{ $history->status ?? '-' }}
            </span>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Work Order
                </p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $history->work_order_number ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Maintenance Date
                </p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $history->maintenance_date
                        ? \Illuminate\Support\Carbon::parse($history->maintenance_date)->format('d M Y')
                        : '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Maintenance Type
                </p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $history->maintenance_type ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Technician
                </p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $history->technician ?: '-' }}
                </p>
            </div>

        </div>
    </section>

    {{-- Equipment Information --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <h2 class="text-lg font-semibold text-slate-800">
            Equipment Information
        </h2>

        <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Equipment Name
                </p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $equipment->equipment_name ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Equipment Code
                </p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $equipment->equipment_code ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Tag Number
                </p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $equipment->tag_number ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Location
                </p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $equipment->location ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Manufacturer
                </p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $equipment->manufacturer ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Model
                </p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $equipment->model ?: '-' }}
                </p>
            </div>

        </div>
    </section>

    {{-- Execution Summary --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <h2 class="text-lg font-semibold text-slate-800">
            Execution Summary
        </h2>

        <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Duration
                </p>
                <p class="mt-1 text-xl font-bold text-slate-800">
                    {{ $history->duration !== null
                        ? number_format($history->duration) . ' minutes'
                        : '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Result
                </p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $history->result ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Record ID
                </p>
                <p class="mt-1 font-semibold text-slate-800">
                    #{{ $history->id }}
                </p>
            </div>

        </div>

        <div class="mt-5 border-t border-slate-100 pt-5">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                Technical Notes
            </p>

            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">
                {{ $history->notes ?: 'No technical notes available.' }}
            </p>
        </div>
    </section>

    {{-- PDF Viewer --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-2 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-slate-800">
                    PDF Document Preview
                </h2>
                <p class="mt-1 text-xs text-slate-500">
                    Review the generated maintenance report below.
                </p>
            </div>

            <a
                href="{{ route('maintenance-history.preview.pdf', [$equipment, $history]) }}"
                target="_blank"
                rel="noopener noreferrer"
                class="text-sm font-semibold text-blue-600 hover:text-blue-800"
            >
                Open PDF in New Tab
            </a>
        </div>

        <div class="bg-slate-100 p-2 sm:p-4">
            <iframe
                src="{{ route('maintenance-history.preview.pdf', [$equipment, $history]) }}"
                title="Maintenance PDF Preview"
                class="h-[750px] w-full rounded-lg border border-slate-200 bg-white sm:h-[900px]"
            ></iframe>
        </div>

    </section>

    {{-- Bottom Actions --}}
    <div class="flex flex-wrap justify-end gap-3">
        <a
            href="{{ route('maintenance.index') }}"
            class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
        >
            Back
        </a>

        <a
            href="{{ route('maintenance-history.export.pdf', [$equipment, $history]) }}"
            class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
        >
            Download Maintenance PDF
        </a>
    </div>

</div>
@endsection
