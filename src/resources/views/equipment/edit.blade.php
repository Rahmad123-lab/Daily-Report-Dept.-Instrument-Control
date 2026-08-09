@extends('layouts.app')

@section('title', 'Edit Equipment')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Edit Equipment
            </h1>

            <p class="text-slate-500">
                Update equipment master data.
            </p>

        </div>

        <a
            href="{{ route('equipment.show', $equipment) }}"
            class="px-5 py-3 rounded-xl border bg-white hover:bg-slate-50">

            Back

        </a>

    </div>

    {{-- Validation --}}
    @if ($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-5">

            <ul class="list-disc ml-5 text-red-600 space-y-1">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form
        method="POST"
        action="{{ route('equipment.update', $equipment) }}"
        class="bg-white rounded-2xl border shadow-sm">

        @csrf
        @method('PUT')

        <div class="p-6 border-b">

            <h2 class="text-xl font-semibold">

                Equipment Information

            </h2>

        </div>

        <div class="p-6 grid md:grid-cols-2 gap-6">

            {{-- Equipment Code --}}
            <div>

                <label class="block text-sm font-medium mb-2">
                    Equipment Code
                </label>

                <input
                    type="text"
                    name="equipment_code"
                    value="{{ old('equipment_code', $equipment->equipment_code) }}"
                    class="w-full rounded-xl border-slate-300">

            </div>

            {{-- Tag Number --}}
            <div>

                <label class="block text-sm font-medium mb-2">
                    Tag Number
                </label>

                <input
                    type="text"
                    name="tag_number"
                    value="{{ old('tag_number', $equipment->tag_number) }}"
                    class="w-full rounded-xl border-slate-300">

            </div>

            {{-- Equipment Name --}}
            <div class="md:col-span-2">

                <label class="block text-sm font-medium mb-2">
                    Equipment Name
                </label>

                <input
                    type="text"
                    name="equipment_name"
                    value="{{ old('equipment_name', $equipment->equipment_name) }}"
                    class="w-full rounded-xl border-slate-300">

            </div>

            {{-- Category --}}
            <div>

                <label class="block text-sm font-medium mb-2">
                    Category
                </label>

                <select
                    name="category"
                    class="w-full rounded-xl border-slate-300">

                   @foreach([
                            'Instrument',
                            'Valve',
                            'Electrical',
                            'Analyzer',
                            'PLC',
                            'DCS',
                            'Motor',
                            'Pump',
                            'Tank',
                            'Utility'
                        ] as $category)

                        <option
                            value="{{ $category }}"
                            @selected(old('category', $equipment->category) == $category)>

                            {{ $category }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- Status --}}
            <div>

                <label class="block text-sm font-medium mb-2">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-xl border-slate-300">

                   @foreach([
                                'Active',
                                'Standby',
                                'Maintenance',
                                'Breakdown',
                                'Decommission'
                            ] as $status)

                        <option
                            value="{{ $status }}"
                            @selected(old('status', $equipment->status) == $status)>

                            {{ $status }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- Location --}}
            <div>

                <label class="block text-sm font-medium mb-2">
                    Location
                </label>

                <input
                    type="text"
                    name="location"
                    value="{{ old('location', $equipment->location) }}"
                    class="w-full rounded-xl border-slate-300">

            </div>

            {{-- Installation Date --}}
            <div>

                <label class="block text-sm font-medium mb-2">
                    Installation Date
                </label>

                <input
                    type="date"
                    name="installation_date"
                    value="{{ old('installation_date', $equipment->installation_date) }}"
                    class="w-full rounded-xl border-slate-300">

            </div>

            {{-- Manufacturer --}}
            <div>

                <label class="block text-sm font-medium mb-2">
                    Manufacturer
                </label>

                <input
                    type="text"
                    name="manufacturer"
                    value="{{ old('manufacturer', $equipment->manufacturer) }}"
                    class="w-full rounded-xl border-slate-300">

            </div>

            {{-- Model --}}
            <div>

                <label class="block text-sm font-medium mb-2">
                    Model
                </label>

                <input
                    type="text"
                    name="model"
                    value="{{ old('model', $equipment->model) }}"
                    class="w-full rounded-xl border-slate-300">

            </div>

            {{-- Serial Number --}}
            <div class="md:col-span-2">

                <label class="block text-sm font-medium mb-2">
                    Serial Number
                </label>

                <input
                    type="text"
                    name="serial_number"
                    value="{{ old('serial_number', $equipment->serial_number) }}"
                    class="w-full rounded-xl border-slate-300">

            </div>

            {{-- Description --}}
            <div class="md:col-span-2">

                <label class="block text-sm font-medium mb-2">
                    Description
                </label>

                <textarea
                    name="description"
                    rows="5"
                    class="w-full rounded-xl border-slate-300">{{ old('description', $equipment->description) }}</textarea>

            </div>

        </div>

        <div class="border-t p-6 flex justify-end gap-3">

            <a
                href="{{ route('equipment.show', $equipment) }}"
                class="px-6 py-3 rounded-xl border">

                Cancel

            </a>

            <button
                type="submit"
                class="px-6 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700">

                Update Equipment

            </button>

        </div>

    </form>

</div>

@endsection