@extends('layouts.app')

@section('title', 'Create Equipment')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">
                Create Equipment
            </h1>

            <p class="text-slate-500">
                Add new instrument or equipment to master database.
            </p>

        </div>

        <a href="{{ route('equipment.index') }}"
           class="px-5 py-3 rounded-xl border bg-white hover:bg-slate-50">

            ← Back

        </a>

    </div>


    {{-- Validation Error --}}
    @if ($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-5">

            <h3 class="font-semibold text-red-700 mb-3">
                Please fix the following errors:
            </h3>

            <ul class="list-disc ml-6 text-red-600 space-y-1">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('equipment.store') }}"
        method="POST">

        @csrf

        <div class="bg-white rounded-2xl border shadow-sm p-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Equipment Code --}}
                <div>

                    <label class="block mb-2 font-medium">
                        Equipment Code
                    </label>

                    <input
                        type="text"
                        name="equipment_code"
                        value="{{ old('equipment_code') }}"
                        class="w-full rounded-xl border-slate-300"
                        required>

                </div>


                {{-- Tag Number --}}
                <div>

                    <label class="block mb-2 font-medium">
                        Tag Number
                    </label>

                    <input
                        type="text"
                        name="tag_number"
                        value="{{ old('tag_number') }}"
                        class="w-full rounded-xl border-slate-300"
                        required>

                </div>


                {{-- Equipment Name --}}
                <div class="md:col-span-2">

                    <label class="block mb-2 font-medium">
                        Equipment Name
                    </label>

                    <input
                        type="text"
                        name="equipment_name"
                        value="{{ old('equipment_name') }}"
                        class="w-full rounded-xl border-slate-300"
                        required>

                </div>


                {{-- Category --}}
                <div>

                    <label class="block mb-2 font-medium">
                        Category
                    </label>

                    <select
                        name="category"
                        class="w-full rounded-xl border-slate-300"
                        required>

                       <option value="">Select Category</option>

<option value="Instrument" @selected(old('category')=='Instrument')>
    Instrument
</option>

<option value="Valve" @selected(old('category')=='Valve')>
    Valve
</option>

<option value="Electrical" @selected(old('category')=='Electrical')>
    Electrical
</option>

<option value="Analyzer" @selected(old('category')=='Analyzer')>
    Analyzer
</option>

<option value="PLC" @selected(old('category')=='PLC')>
    PLC
</option>

<option value="DCS" @selected(old('category')=='DCS')>
    DCS
</option>

<option value="Motor" @selected(old('category')=='Motor')>
    Motor
</option>

<option value="Pump" @selected(old('category')=='Pump')>
    Pump
</option>

<option value="Tank" @selected(old('category')=='Tank')>
    Tank
</option>

<option value="Utility" @selected(old('category')=='Utility')>
    Utility
</option>

                    </select>

                </div>


                {{-- Status --}}
                <div>

                    <label class="block mb-2 font-medium">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-xl border-slate-300"
                        required>

                        <option value="Active">Active</option>

<option value="Standby">Standby</option>

<option value="Maintenance">Maintenance</option>

<option value="Breakdown">Breakdown</option>

<option value="Decommission">Decommission</option>

                    </select>

                </div>


                {{-- Location --}}
                <div>

                    <label class="block mb-2 font-medium">
                        Location
                    </label>

                    <input
                        type="text"
                        name="location"
                        value="{{ old('location') }}"
                        class="w-full rounded-xl border-slate-300">

                </div>


                {{-- Installation Date --}}
                <div>

                    <label class="block mb-2 font-medium">
                        Installation Date
                    </label>

                    <input
                        type="date"
                        name="installation_date"
                        value="{{ old('installation_date') }}"
                        class="w-full rounded-xl border-slate-300">

                </div>


                {{-- Manufacturer --}}
                <div>

                    <label class="block mb-2 font-medium">
                        Manufacturer
                    </label>

                    <input
                        type="text"
                        name="manufacturer"
                        value="{{ old('manufacturer') }}"
                        class="w-full rounded-xl border-slate-300">

                </div>


                {{-- Model --}}
                <div>

                    <label class="block mb-2 font-medium">
                        Model
                    </label>

                    <input
                        type="text"
                        name="model"
                        value="{{ old('model') }}"
                        class="w-full rounded-xl border-slate-300">

                </div>


                {{-- Serial Number --}}
                <div class="md:col-span-2">

                    <label class="block mb-2 font-medium">
                        Serial Number
                    </label>

                    <input
                        type="text"
                        name="serial_number"
                        value="{{ old('serial_number') }}"
                        class="w-full rounded-xl border-slate-300">

                </div>


                {{-- Description --}}
                <div class="md:col-span-2">

                    <label class="block mb-2 font-medium">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        class="w-full rounded-xl border-slate-300">{{ old('description') }}</textarea>

                </div>

            </div>


            <div class="flex justify-end gap-3 mt-8">

                <a
                    href="{{ route('equipment.index') }}"
                    class="px-6 py-3 rounded-xl border">

                    Cancel

                </a>

                <button
                    type="submit"
                    class="px-6 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700">

                    Save Equipment

                </button>

            </div>

        </div>

    </form>

</div>

@endsection