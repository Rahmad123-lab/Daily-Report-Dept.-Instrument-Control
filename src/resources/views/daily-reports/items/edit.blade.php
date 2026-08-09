@extends('layouts.app')

@section('title', 'Edit Daily Report')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">
                Edit Daily Report
            </h1>

            <p class="text-slate-500">
                {{ $dailyReport->report_number }}
            </p>

        </div>

        <a
            href="{{ route(
                'daily-reports.show',
                $dailyReport
            ) }}"
            class="px-5 py-3 rounded-xl border bg-white">

            ← Back

        </a>

    </div>


    <div class="bg-white rounded-2xl border shadow-sm p-6">

        <form
            method="POST"
            action="{{ route(
                'daily-reports.update',
                $dailyReport
            ) }}"
            class="space-y-6">

            @csrf

            @method('PUT')


            {{-- Date --}}

            <div>

                <label class="block text-sm font-medium mb-2">
                    Report Date
                </label>

                <input
                    type="date"
                    name="report_date"
                    value="{{ old(
                        'report_date',
                        $dailyReport->report_date
                            ->format('Y-m-d')
                    ) }}"
                    class="w-full rounded-xl border-slate-300">

            </div>


            {{-- Shift --}}

            <div>

                <label class="block text-sm font-medium mb-2">
                    Shift
                </label>

                <select
                    name="shift"
                    class="w-full rounded-xl border-slate-300">

                   @foreach([
    'Non Shift',
    'Piket Malam'
] as $shift)

<option
    value="{{ $shift }}"
    @selected(old('shift', $dailyReport->shift) == $shift)>

    {{ $shift }}

</option>

@endforeach

                </select>

            </div>


            {{-- Division --}}

            <div>

                <label class="block text-sm font-medium mb-2">
                    Division
                </label>

                <input
                    type="text"
                    name="division"
                    value="{{ old(
                        'division',
                        $dailyReport->division
                    ) }}"
                    class="w-full rounded-xl border-slate-300">

            </div>


            {{-- Notes --}}

            <div>

                <label class="block text-sm font-medium mb-2">
                    Notes
                </label>

                <textarea
                    name="notes"
                    rows="5"
                    class="w-full rounded-xl border-slate-300">{{ old(
                        'notes',
                        $dailyReport->notes
                    ) }}</textarea>

            </div>


            <div class="flex justify-end gap-3">

                <a
                    href="{{ route(
                        'daily-reports.show',
                        $dailyReport
                    ) }}"
                    class="px-6 py-3 rounded-xl border">

                    Cancel

                </a>


                <button
                    type="submit"
                    class="px-6 py-3 rounded-xl bg-blue-600 text-white">

                    Update Report

                </button>

            </div>

        </form>

    </div>

</div>

@endsection