@extends('layouts.app')

@section('title', 'Edit Equipment')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Edit Equipment
            </h1>

            <p class="text-slate-500 mt-1">
                Update equipment master data.
            </p>
        </div>

        <a
            href="{{ route('equipment.show', $equipment) }}"
            class="inline-flex items-center justify-center px-5 py-3 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition"
        >
            ← Back
        </a>

    </div>


    {{-- Validation Error --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-5">

            <div class="flex items-start gap-3">

                <div class="text-red-600 text-xl">
                    !
                </div>

                <div>
                    <h3 class="font-semibold text-red-700 mb-2">
                        Please fix the following errors:
                    </h3>

                    <ul class="list-disc ml-5 text-red-600 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>

        </div>
    @endif


    {{-- Form --}}
    <form
        method="POST"
        action="{{ route('equipment.update', $equipment) }}"
    >
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Section Header --}}
            <div class="px-6 py-5 border-b border-slate-200">

                <h2 class="text-lg font-semibold text-slate-800">
                    Equipment Information
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Update the information of this equipment.
                </p>

            </div>


            {{-- Form Fields --}}
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Equipment Code --}}
                <div>
                    <label
                        for="equipment_code"
                        class="block mb-2 text-sm font-medium text-slate-700"
                    >
                        Equipment Code
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="equipment_code"
                        type="text"
                        name="equipment_code"
                        value="{{ old('equipment_code', $equipment->equipment_code) }}"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        required
                    >
                </div>


                {{-- Tag Number --}}
                <div>
                    <label
                        for="tag_number"
                        class="block mb-2 text-sm font-medium text-slate-700"
                    >
                        Tag Number
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="tag_number"
                        type="text"
                        name="tag_number"
                        value="{{ old('tag_number', $equipment->tag_number) }}"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        required
                    >
                </div>


                {{-- Equipment Name --}}
                <div class="md:col-span-2">
                    <label
                        for="equipment_name"
                        class="block mb-2 text-sm font-medium text-slate-700"
                    >
                        Equipment Name
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="equipment_name"
                        type="text"
                        name="equipment_name"
                        value="{{ old('equipment_name', $equipment->equipment_name) }}"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        required
                    >
                </div>


                {{-- Category --}}
                <div>
                    <label
                        for="category"
                        class="block mb-2 text-sm font-medium text-slate-700"
                    >
                        Category
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="category"
                        name="category"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        required
                    >
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
                            'Utility',
                        ] as $category)

                            <option
                                value="{{ $category }}"
                                @selected(old('category', $equipment->category) === $category)
                            >
                                {{ $category }}
                            </option>

                        @endforeach
                    </select>
                </div>


                {{-- Status --}}
                <div>
    <label
        for="status"
        class="block text-sm font-medium text-slate-700 mb-2"
    >
        Status
        <span class="text-red-500">*</span>
    </label>

    <select
        id="status"
        name="status"
        required
        class="w-full rounded-xl border border-slate-300
               px-4 py-2.5 text-sm bg-white
               focus:border-blue-500 focus:ring-2
               focus:ring-blue-100 outline-none transition"
    >

        @foreach ([
            'Open',
            'Progress',
            'Completed',
        ] as $status)

            <option
                value="{{ $status }}"
                @selected(
                    old(
                        'status',
                        $history->status
                    ) === $status
                )
            >
                {{ $status }}
            </option>

        @endforeach

    </select>
</div>


                {{-- Location --}}
                <div>
                    <label
                        for="location"
                        class="block mb-2 text-sm font-medium text-slate-700"
                    >
                        Location
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="location"
                        type="text"
                        name="location"
                        value="{{ old('location', $equipment->location) }}"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        required
                    >
                </div>


                {{-- Installation Date --}}
                <div>
                    <label
                        for="installation_date"
                        class="block mb-2 text-sm font-medium text-slate-700"
                    >
                        Installation Date
                    </label>

                    <input
                        id="installation_date"
                        type="date"
                        name="installation_date"
                        value="{{ old(
                            'installation_date',
                            $equipment->installation_date?->format('Y-m-d')
                        ) }}"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>


                {{-- Manufacturer --}}
                <div>
                    <label
                        for="manufacturer"
                        class="block mb-2 text-sm font-medium text-slate-700"
                    >
                        Manufacturer
                    </label>

                    <input
                        id="manufacturer"
                        type="text"
                        name="manufacturer"
                        value="{{ old('manufacturer', $equipment->manufacturer) }}"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>


                {{-- Model --}}
                <div>
                    <label
                        for="model"
                        class="block mb-2 text-sm font-medium text-slate-700"
                    >
                        Model
                    </label>

                    <input
                        id="model"
                        type="text"
                        name="model"
                        value="{{ old('model', $equipment->model) }}"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>


                {{-- Serial Number --}}
                <div class="md:col-span-2">
                    <label
                        for="serial_number"
                        class="block mb-2 text-sm font-medium text-slate-700"
                    >
                        Serial Number
                    </label>

                    <input
                        id="serial_number"
                        type="text"
                        name="serial_number"
                        value="{{ old('serial_number', $equipment->serial_number) }}"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>


                {{-- Description --}}
                <div class="md:col-span-2">
                    <label
                        for="description"
                        class="block mb-2 text-sm font-medium text-slate-700"
                    >
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('description', $equipment->description) }}</textarea>
                </div>

            </div>


            {{-- Footer --}}
            <div class="border-t border-slate-200 px-6 py-5 flex flex-col-reverse sm:flex-row justify-end gap-3">

                <a
                    href="{{ route('equipment.show', $equipment) }}"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700 transition"
                >
                    Update Equipment
                </button>

            </div>

        </div>

    </form>

</div>

@endsection