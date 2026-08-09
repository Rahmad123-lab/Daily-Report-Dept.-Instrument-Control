@extends('layouts.app')

@section('title', 'Edit Daily Report')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">
                Edit Daily Report
            </h1>

            <p class="text-slate-500">
                Update informasi laporan harian
            </p>

        </div>

        <a href="{{ route('daily-reports.show',$dailyReport) }}"
           class="px-5 py-3 rounded-xl border bg-white">

            ← Back

        </a>

    </div>


    <div class="bg-white rounded-2xl shadow border p-8">

        <form
            method="POST"
            action="{{ route('daily-reports.update',$dailyReport) }}">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-6">

                <div>

                    <label class="block mb-2 font-medium">

                        Report Date

                    </label>

                    <input
                        type="date"
                        name="report_date"
                        value="{{ old('report_date',$dailyReport->report_date->format('Y-m-d')) }}"
                        class="w-full rounded-xl border-slate-300">

                </div>

                <div>

                    <label class="block mb-2 font-medium">

                        Shift

                    </label>

                    <select
                                name="shift"
                                required
                                class="w-full rounded-xl border-slate-300 px-4 py-3">

                                <option value="Non Shift"
                                    @selected(old('shift', $dailyReport->shift) === 'Non Shift')>
                                    Non Shift
                                </option>

                                <option value="Piket Malam"
                                    @selected(old('shift', $dailyReport->shift) === 'Piket Malam')>
                                    Piket Malam
                                </option>

                    </select>
                </div>

            </div>


            <div class="mt-6">

                <label class="block mb-2 font-medium">

                    Division

                </label>

                <input
                    type="text"
                    name="division"
                    value="{{ old('division',$dailyReport->division) }}"
                    class="w-full rounded-xl border-slate-300">

            </div>


            <div class="mt-6">

                <label class="block mb-2 font-medium">

                    Notes

                </label>

                <textarea
                    rows="6"
                    name="notes"
                    class="w-full rounded-xl border-slate-300">{{ old('notes',$dailyReport->notes) }}</textarea>

            </div>


            <div class="flex justify-end gap-3 mt-8">

                <a
                    href="{{ route('daily-reports.show',$dailyReport) }}"
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