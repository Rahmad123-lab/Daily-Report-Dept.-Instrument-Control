@extends('layouts.app')

@section('title', 'Create Daily Report')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Create Daily Report
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Create daily activity report for Instrument & Control System
            </p>
        </div>

        <a href="{{ route('daily-reports.index') }}"
           class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">

            ← Back

        </a>

    </div>


    {{-- Error --}}
    @if ($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <ul class="list-disc pl-5 text-sm text-red-700">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route('daily-reports.store') }}"
          class="space-y-6">

        @csrf


        {{-- Basic Information --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="mb-6 text-lg font-bold text-slate-800">
                Report Information
            </h2>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Report Date
                    </label>

                    <input
                        type="date"
                        name="report_date"
                        value="{{ old('report_date', now()->format('Y-m-d')) }}"
                        required
                        class="w-full rounded-xl border-slate-300 px-4 py-3"
                    >

                </div>


                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Shift
                    </label>

                    <select
    name="shift"
    class="w-full rounded-xl border-slate-300">

    <option value="">Select Shift</option>

    <option value="Non Shift"
        @selected(old('shift')=='Non Shift')>
        Non Shift
    </option>

    <option value="Piket Malam"
        @selected(old('shift')=='Piket Malam')>
        Piket Malam
    </option>

</select>
                </div>


                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Division
                    </label>

                    <input
                        type="text"
                        name="division"
                        value="{{ old('division', 'Power Plant') }}"
                        required
                        class="w-full rounded-xl border-slate-300 px-4 py-3"
                    >

                </div>

            </div>

        </div>


        {{-- Work Items --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <div class="mb-6 flex items-center justify-between">

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        Work Activities
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Add all activities performed during this report.
                    </p>

                </div>

                <button
                    type="button"
                    onclick="addWorkItem()"
                    class="rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700"
                >
                    + Add Work
                </button>

            </div>


            <div id="work-items" class="space-y-6">

                {{-- First item --}}
                <div class="work-item rounded-2xl border border-slate-200 bg-slate-50 p-5">

                    <div class="mb-5 flex items-center justify-between">

                        <h3 class="font-bold text-slate-800">
                            Work #1
                        </h3>

                        <button
                            type="button"
                            onclick="removeWorkItem(this)"
                            class="text-sm font-semibold text-red-600 hover:text-red-800"
                        >
                            Remove
                        </button>

                    </div>


                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        {{-- Category --}}
                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Category
                            </label>

                            <select
                                name="items[0][category]"
                                required
                                class="w-full rounded-xl border-slate-300 px-4 py-3"
                            >

                                <option value="">
                                    Select Category
                                </option>

                                <option>Perintah Atasan</option>
                                <option>Pekerjaan Rutin</option>
                                <option>Buku Cacat</option>
                                <option>Preventive</option>
                                <option>Corrective</option>
                                <option>Emergency</option>

                            </select>

                        </div>


                        {{-- SPK --}}
                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                No. SPK
                            </label>

                            <input
                                type="text"
                                name="items[0][spk_number]"
                                placeholder="Optional"
                                class="w-full rounded-xl border-slate-300 px-4 py-3"
                            >

                        </div>


                        {{-- Title --}}
                        <div class="md:col-span-2">

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Work Title
                            </label>

                            <input
                                type="text"
                                name="items[0][title]"
                                placeholder="Example: Kalibrasi Zero dan Span Belt Scale"
                                required
                                class="w-full rounded-xl border-slate-300 px-4 py-3"
                            >

                        </div>


                        {{-- Description --}}
                        <div class="md:col-span-2">

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Work Description
                            </label>

                            <textarea
                                name="items[0][description]"
                                rows="5"
                                required
                                placeholder="Describe work activities and results..."
                                class="w-full rounded-xl border-slate-300 px-4 py-3"
                            ></textarea>

                        </div>


                        {{-- Status --}}
                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Status
                            </label>

                            <select
                                name="items[0][status]"
                                required
                                class="w-full rounded-xl border-slate-300 px-4 py-3"
                            >

                                <option value="Open">
                                    Open
                                </option>

                                <option value="Progress">
                                    Progress
                                </option>

                                <option value="Done">
                                    Done
                                </option>

                            </select>

                        </div>


                        {{-- Workers --}}
                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Workers
                            </label>

                            <select
                                name="items[0][workers][]"
                                multiple
                                class="w-full rounded-xl border-slate-300 px-4 py-3"
                            >

                                @foreach($workers as $worker)

                                    <option value="{{ $worker->id }}">
                                        {{ $worker->name }} - {{ $worker->nik }}
                                    </option>

                                @endforeach

                            </select>

                            <p class="mt-1 text-xs text-slate-500">
                                Hold Ctrl to select multiple workers.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Notes --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <label class="mb-2 block text-sm font-medium text-slate-700">
                Notes
            </label>

            <textarea
                name="notes"
                rows="4"
                placeholder="Additional notes..."
                class="w-full rounded-xl border-slate-300 px-4 py-3"
            >{{ old('notes') }}</textarea>

        </div>


        {{-- Submit --}}
        <div class="flex justify-end gap-3">

            <a
                href="{{ route('daily-reports.index') }}"
                class="rounded-xl border border-slate-300 px-6 py-3 font-semibold text-slate-600 hover:bg-slate-50"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700"
            >
                Save Daily Report
            </button>

        </div>

    </form>

</div>


<script>

let workIndex = 1;

function addWorkItem()
{
    const container = document.getElementById('work-items');

    const template = document.querySelector('.work-item').cloneNode(true);

    template.querySelectorAll('input, textarea, select').forEach(element => {

        element.name = element.name.replace(
            /items\[\d+\]/,
            `items[${workIndex}]`
        );

        if (element.tagName === 'SELECT') {

            element.selectedIndex = 0;

        } else {

            element.value = '';

        }

    });

    template.querySelector('h3').innerText =
        `Work #${workIndex + 1}`;

    container.appendChild(template);

    workIndex++;
}


function removeWorkItem(button)
{
    const items = document.querySelectorAll('.work-item');

    if (items.length <= 1) {

        alert('At least one work activity is required.');

        return;

    }

    button.closest('.work-item').remove();
}

</script>

@endsection