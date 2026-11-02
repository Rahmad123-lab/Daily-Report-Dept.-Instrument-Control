
@extends('layouts.app')

@section('title', 'Daily Report Preview')
@section('header', 'Daily Report Preview')

@section('content')

<div class="space-y-5">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="flex flex-col gap-4 lg:flex-row
                lg:items-center lg:justify-between">

        <div>
            <div class="flex items-center gap-2">

                <a href="{{ route('daily-reports.show', $dailyReport) }}"
                   class="text-sm text-slate-500 transition
                          hover:text-blue-600">
                    Daily Reports
                </a>

                <span class="text-slate-300">/</span>

                <span class="text-sm font-semibold text-slate-800">
                    Preview
                </span>

            </div>

            <h1 class="mt-3 text-2xl font-bold text-slate-800">
                PDF Document Preview
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Periksa dokumen laporan sebelum mengunduh PDF.
            </p>
        </div>


        {{-- ACTION BUTTONS --}}
        <div class="flex flex-wrap items-center gap-2">

            <a
                href="{{ route('daily-reports.show', $dailyReport) }}"
                class="inline-flex items-center justify-center gap-2
                       rounded-xl border border-slate-300
                       bg-white px-4 py-2.5 text-sm
                       font-semibold text-slate-700
                       transition hover:bg-slate-50"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15 19l-7-7 7-7"/>
                </svg>

                Back to Report
            </a>


            <a
                href="{{ route('daily-reports.export.pdf', $dailyReport) }}"
                class="inline-flex items-center justify-center gap-2
                       rounded-xl bg-blue-600 px-5 py-2.5
                       text-sm font-semibold text-white
                       shadow-sm transition hover:bg-blue-700"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
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


    {{-- =====================================================
         DOCUMENT INFORMATION
    ====================================================== --}}

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <p class="text-xs font-medium text-slate-500">
                Report Number
            </p>

            <p class="mt-2 text-sm font-bold text-slate-800">
                {{ $dailyReport->report_number }}
            </p>
        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <p class="text-xs font-medium text-slate-500">
                Report Date
            </p>

            <p class="mt-2 text-sm font-bold text-slate-800">
                {{ $dailyReport->report_date?->format('d M Y') ?? '-' }}
            </p>
        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <p class="text-xs font-medium text-slate-500">
                Shift
            </p>

            <p class="mt-2 text-sm font-bold text-slate-800">
                {{ $dailyReport->shift }}
            </p>
        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <p class="text-xs font-medium text-slate-500">
                Document Status
            </p>

            <div class="mt-2">
                <span class="rounded-full px-3 py-1 text-xs
                    font-semibold
                    {{ $dailyReport->status === 'Published'
                        ? 'bg-emerald-50 text-emerald-700'
                        : 'bg-amber-50 text-amber-700' }}">
                    {{ $dailyReport->status }}
                </span>
            </div>
        </div>

    </div>


    {{-- =====================================================
         PREVIEW INFORMATION
    ====================================================== --}}

    <div class="flex flex-col gap-3 rounded-xl border
                border-blue-100 bg-blue-50 p-4 sm:flex-row
                sm:items-center sm:justify-between">

        <div class="flex items-start gap-3">

            <div class="flex h-9 w-9 shrink-0 items-center
                        justify-center rounded-lg bg-white
                        text-blue-600">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 9v4m0 4h.01"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>

            </div>

            <div>
                <p class="text-sm font-semibold text-blue-900">
                    Document Preview
                </p>

                <p class="mt-1 text-xs leading-5 text-blue-700">
                    Tampilan di bawah merupakan PDF yang sama dengan
                    dokumen yang akan diunduh.
                </p>
            </div>

        </div>

        <span class="shrink-0 text-xs font-medium text-blue-700">
            A4 Landscape
        </span>

    </div>


    {{-- =====================================================
         PDF VIEWER
    ====================================================== --}}

    <div class="overflow-hidden rounded-2xl border
                border-slate-200 bg-white shadow-sm">

        <div class="flex items-center justify-between
                    border-b border-slate-200 px-5 py-4">

            <div class="flex items-center gap-3">

                <div class="flex h-9 w-9 items-center
                            justify-center rounded-lg bg-red-50
                            text-red-600">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M14 3v5h5"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 14h6M9 17h6"/>
                    </svg>

                </div>

                <div>
                    <p class="text-sm font-semibold text-slate-800">
                        Daily Operational Report
                    </p>

                    <p class="text-xs text-slate-500">
                        {{ $dailyReport->report_number }}
                    </p>
                </div>

            </div>

            <a
                href="{{ route('daily-reports.preview.pdf', $dailyReport) }}"
                target="_blank"
                rel="noopener"
                class="text-xs font-semibold text-blue-600
                       transition hover:text-blue-800"
            >
                Open in New Tab
            </a>

        </div>


        <div class="bg-slate-200 p-2 sm:p-4">

            <iframe
                src="{{ route('daily-reports.preview.pdf', $dailyReport) }}"
                title="Daily Report PDF Preview"
                class="block w-full rounded-lg bg-white"
                style="height: 78vh; min-height: 600px;"
            ></iframe>

        </div>

    </div>


    {{-- =====================================================
         BOTTOM ACTIONS
    ====================================================== --}}

    <div class="flex flex-col items-center justify-between
                gap-3 rounded-xl border border-slate-200
                bg-white p-5 sm:flex-row">

        <div>
            <p class="text-sm font-semibold text-slate-800">
                Document ready?
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Unduh PDF setelah memastikan seluruh informasi benar.
            </p>
        </div>

        <a
            href="{{ route('daily-reports.export.pdf', $dailyReport) }}"
            class="inline-flex w-full items-center justify-center
                   gap-2 rounded-xl bg-blue-600 px-6 py-3
                   text-sm font-semibold text-white
                   transition hover:bg-blue-700 sm:w-auto"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="1.8">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 3v12m0 0 4-4m-4 4-4-4"/>
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M5 17v3h14v-3"/>
            </svg>

            Download Daily Report PDF
        </a>

    </div>

</div>

@endsection
