@extends('layouts.app')

@section('title', 'Maintenance Report Preview')
@section('header', 'Maintenance Report Preview')

@section('content')

<div class="space-y-5">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col gap-4 lg:flex-row
                lg:items-center lg:justify-between">

        <div>

            <div class="flex items-center gap-2 text-sm">

                <a href="{{ route('maintenance.index') }}"
                   class="text-slate-500 hover:text-blue-600">
                    Maintenance
                </a>

                <span class="text-slate-300">/</span>

                <span class="font-semibold text-slate-800">
                    Preview
                </span>

            </div>

            <h1 class="mt-3 text-2xl font-bold text-slate-800">
                Maintenance PDF Preview
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Periksa laporan maintenance sebelum mengunduh dokumen PDF.
            </p>

        </div>


        <div class="flex flex-wrap gap-2">

            <a
                href="{{ route('maintenance.index') }}"
                class="inline-flex items-center gap-2 rounded-xl
                       border border-slate-300 bg-white px-4 py-2.5
                       text-sm font-semibold text-slate-700
                       hover:bg-slate-50"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="m15 19-7-7 7-7"/>
                </svg>

                Back to Maintenance
            </a>


            <a
                href="{{ route('maintenance-history.export.pdf', [
                    'equipment' => $equipment->id,
                    'history' => $history->id,
                ]) }}"
                class="inline-flex items-center gap-2 rounded-xl
                       bg-blue-600 px-5 py-2.5 text-sm font-semibold
                       text-white shadow-sm hover:bg-blue-700"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 3v12m0 0 4-4m-4 4-4-4"/>
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M5 17v3h14v-3"/>
                </svg>

                Download PDF
            </a>

        </div>

    </div>


    {{-- REPORT INFORMATION --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <p class="text-xs text-slate-500">Report Reference</p>

            <p class="mt-2 text-sm font-bold text-slate-800">
                MNT-{{ $history->id }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <p class="text-xs text-slate-500">Equipment</p>

            <p class="mt-2 text-sm font-bold text-slate-800">
                {{ $equipment->equipment_name }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <p class="text-xs text-slate-500">Maintenance Date</p>

            <p class="mt-2 text-sm font-bold text-slate-800">
                {{ $history->maintenance_date?->format('d M Y') ?? '-' }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <p class="text-xs text-slate-500">Maintenance Status</p>

            <div class="mt-2">
                <span class="inline-flex rounded-full px-3 py-1 text-xs
                    font-semibold
                    {{ $history->status === 'Completed'
                        ? 'bg-emerald-50 text-emerald-700'
                        : ($history->status === 'Progress'
                            ? 'bg-amber-50 text-amber-700'
                            : 'bg-red-50 text-red-700') }}">
                    {{ $history->status }}
                </span>
            </div>
        </div>

    </div>


    {{-- PREVIEW NOTICE --}}
    <div class="flex flex-col gap-3 rounded-xl border
                border-blue-100 bg-blue-50 p-4 sm:flex-row
                sm:items-center sm:justify-between">

        <div>
            <p class="text-sm font-semibold text-blue-900">
                Document Preview
            </p>

            <p class="mt-1 text-xs text-blue-700">
                Dokumen di bawah menggunakan generator PDF yang sama
                dengan file yang akan diunduh.
            </p>
        </div>

        <span class="text-xs font-semibold text-blue-700">
            A4 Portrait
        </span>

    </div>


    {{-- PDF VIEWER --}}
    <div class="overflow-hidden rounded-2xl border
                border-slate-200 bg-white shadow-sm">

        <div class="flex items-center justify-between
                    border-b border-slate-200 px-5 py-4">

            <div>
                <p class="text-sm font-semibold text-slate-800">
                    Maintenance Report
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    {{ $equipment->equipment_code }}
                    · MNT-{{ $history->id }}
                </p>
            </div>

            <a
                href="{{ route('maintenance-history.preview.pdf', [
                    'equipment' => $equipment->id,
                    'history' => $history->id,
                ]) }}"
                target="_blank"
                rel="noopener"
                class="text-xs font-semibold text-blue-600
                       hover:text-blue-800"
            >
                Open in New Tab
            </a>

        </div>

        <div class="bg-slate-200 p-2 sm:p-4">

            <iframe
                src="{{ route('maintenance-history.preview.pdf', [
                    'equipment' => $equipment->id,
                    'history' => $history->id,
                ]) }}"
                title="Maintenance PDF Preview"
                class="block w-full rounded-lg bg-white"
                style="height: 78vh; min-height: 600px;"
            ></iframe>

        </div>

    </div>


    {{-- DOWNLOAD ACTION --}}
    <div class="flex flex-col gap-3 rounded-xl border
                border-slate-200 bg-white p-5 sm:flex-row
                sm:items-center sm:justify-between">

        <div>
            <p class="text-sm font-semibold text-slate-800">
                Maintenance report ready?
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Unduh dokumen setelah memastikan informasi sudah benar.
            </p>
        </div>

        <a
            href="{{ route('maintenance-history.export.pdf', [
                'equipment' => $equipment->id,
                'history' => $history->id,
            ]) }}"
            class="inline-flex items-center justify-center gap-2
                   rounded-xl bg-blue-600 px-6 py-3
                   text-sm font-semibold text-white
                   hover:bg-blue-700"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor"
                 stroke-width="1.8">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 3v12m0 0 4-4m-4 4-4-4"/>
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M5 17v3h14v-3"/>
            </svg>

            Download Maintenance PDF
        </a>

    </div>

</div>

@endsection
