@extends('layouts.app')

@section('title', 'Daily Reports')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Daily Reports
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Daily activity report of Instrument & Control System Department
            </p>
        </div>

        <a href="{{ route('daily-reports.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">

            <span class="text-lg">+</span>

            Create Report

        </a>

    </div>


    {{-- Filter --}}
    <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-200">

        <form method="GET"
              action="{{ route('daily-reports.index') }}"
              class="grid grid-cols-1 gap-4 md:grid-cols-5">

            {{-- Search --}}
            <div class="md:col-span-2">

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Report number or division..."
                    class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                >

            </div>


            {{-- Date --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Date
                </label>

                <input
                    type="date"
                    name="date"
                    value="{{ request('date') }}"
                    class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                >

            </div>


            {{-- Shift --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Shift
                </label>

                <select
                    name="shift"
                    class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                >

                    <option value="">
                        All Shift
                    </option>

                    <option value="Day"
                        {{ request('shift') == 'Day' ? 'selected' : '' }}>
                        Day
                    </option>

                    <option value="Night"
                        {{ request('shift') == 'Night' ? 'selected' : '' }}>
                        Night
                    </option>

                </select>

            </div>


            {{-- Status --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                >

                    <option value="">
                        All Status
                    </option>

                    <option value="Draft"
                        {{ request('status') == 'Draft' ? 'selected' : '' }}>
                        Draft
                    </option>

                    <option value="Submitted"
                        {{ request('status') == 'Submitted' ? 'selected' : '' }}>
                        Submitted
                    </option>

                    <option value="Approved"
                        {{ request('status') == 'Approved' ? 'selected' : '' }}>
                        Approved
                    </option>

                </select>

            </div>


            {{-- Button --}}
            <div class="md:col-span-5 flex gap-3">

                <button
                    type="submit"
                    class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800"
                >
                    Filter
                </button>

                <a
                    href="{{ route('daily-reports.index') }}"
                    class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="border-b bg-slate-50 text-xs uppercase text-slate-500">

                    <tr>

                        <th class="px-6 py-4">
                            Report Number
                        </th>

                        <th class="px-6 py-4">
                            Date
                        </th>

                        <th class="px-6 py-4">
                            Shift
                        </th>

                        <th class="px-6 py-4">
                            Division
                        </th>

                        <th class="px-6 py-4">
                            Creator
                        </th>

                        <th class="px-6 py-4">
                            Status
                        </th>

                        <th class="px-6 py-4 text-right">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($reports as $report)

                        <tr class="hover:bg-slate-50">

                            {{-- Report Number --}}
                            <td class="px-6 py-5">

                                <div class="font-semibold text-slate-800">
                                    {{ $report->report_number }}
                                </div>

                            </td>


                            {{-- Date --}}
                            <td class="px-6 py-5 text-slate-600">

                                {{ $report->report_date?->format('d M Y') }}

                            </td>


                            {{-- Shift --}}
                            <td class="px-6 py-5">

                                <span class="rounded-lg bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">

                                    {{ $report->shift }}

                                </span>

                            </td>


                            {{-- Division --}}
                            <td class="px-6 py-5 text-slate-600">

                                {{ $report->division }}

                            </td>


                            {{-- Creator --}}
                            <td class="px-6 py-5 text-slate-600">

                                {{ $report->creator?->name ?? '-' }}

                            </td>


                            {{-- Status --}}
                            <td class="px-6 py-5">

                                @php

                                    $statusClass = match ($report->status) {

                                        'Draft' =>
                                            'bg-slate-100 text-slate-700',

                                        'Submitted' =>
                                            'bg-blue-100 text-blue-700',

                                        'Approved' =>
                                            'bg-emerald-100 text-emerald-700',

                                        'Rejected' =>
                                            'bg-red-100 text-red-700',

                                        default =>
                                            'bg-slate-100 text-slate-700',

                                    };

                                @endphp


                                <span class="rounded-lg px-3 py-1 text-xs font-semibold {{ $statusClass }}">

                                    {{ $report->status }}

                                </span>

                            </td>


                            {{-- Action --}}
                            <td class="px-6 py-5">

                                <div class="flex justify-end gap-2">

                                    <a
                                        href="{{ route('daily-reports.show', $report) }}"
                                        class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50"
                                    >
                                        View
                                    </a>

                                    <a
                                        href="{{ route('daily-reports.edit', $report) }}"
                                        class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100"
                                    >
                                        Edit
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="px-6 py-16 text-center text-slate-500"
                            >

                                <div class="text-4xl">
                                    📄
                                </div>

                                <p class="mt-3 font-medium">
                                    No daily reports found
                                </p>

                                <p class="mt-1 text-sm">
                                    Create your first daily report.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($reports->hasPages())

            <div class="border-t border-slate-200 px-6 py-4">

                {{ $reports->links() }}

            </div>

        @endif

    </div>

</div>

@endsection