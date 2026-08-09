@extends('layouts.app')

@section('title', 'Equipment Detail')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- Success --}}
    @if(session('success'))

        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">

            {{ session('success') }}

        </div>

    @endif


    {{-- Header --}}
    <div class="flex justify-between items-center">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                {{ $equipment->equipment_name }}

            </h1>

            <p class="text-slate-500">

                {{ $equipment->tag_number }}

            </p>

        </div>

        <div class="flex gap-3">

            <a
                href="{{ route('equipment.index') }}"
                class="px-5 py-3 rounded-xl border bg-white hover:bg-slate-50">

                Back

            </a>

            <a
                href="{{ route('equipment.edit',$equipment) }}"
                class="px-5 py-3 rounded-xl bg-yellow-500 text-white hover:bg-yellow-600">

                Edit

            </a>

        </div>

    </div>


    {{-- Equipment Information --}}
    <div class="bg-white rounded-2xl border shadow-sm">

        <div class="p-6 border-b">

            <h2 class="text-xl font-semibold">

                Equipment Information

            </h2>

        </div>

        <div class="grid md:grid-cols-2 gap-8 p-6">

            <div>

                <label class="text-slate-500 text-sm">

                    Equipment Code

                </label>

                <p class="font-semibold">

                    {{ $equipment->equipment_code }}

                </p>

            </div>

            <div>

                <label class="text-slate-500 text-sm">

                    Tag Number

                </label>

                <p class="font-semibold">

                    {{ $equipment->tag_number }}

                </p>

            </div>

            <div>

                <label class="text-slate-500 text-sm">

                    Category

                </label>

                <p>

                    {{ $equipment->category }}

                </p>

            </div>

            <div>

                <label class="text-slate-500 text-sm">

                    Status

                </label>

                <p>

                    @php

                        $badge = match($equipment->status){

                            'Active' => 'bg-green-100 text-green-700',

                            'Maintenance' => 'bg-yellow-100 text-yellow-700',

                            'Repair' => 'bg-red-100 text-red-700',

                            default => 'bg-slate-100 text-slate-700'

                        };

                    @endphp

                    <span class="px-3 py-1 rounded-full {{ $badge }}">

                        {{ $equipment->status }}

                    </span>

                </p>

            </div>

            <div>

                <label class="text-slate-500 text-sm">

                    Location

                </label>

                <p>

                    {{ $equipment->location }}

                </p>

            </div>

            <div>

                <label class="text-slate-500 text-sm">

                    Installation Date

                </label>

                <p>

                    {{ $equipment->installation_date
                        ? \Carbon\Carbon::parse($equipment->installation_date)->format('d M Y')
                        : '-'
                    }}

                </p>

            </div>

            <div>

                <label class="text-slate-500 text-sm">

                    Manufacturer

                </label>

                <p>

                    {{ $equipment->manufacturer ?: '-' }}

                </p>

            </div>

            <div>

                <label class="text-slate-500 text-sm">

                    Model

                </label>

                <p>

                    {{ $equipment->model ?: '-' }}

                </p>

            </div>

            <div>

                <label class="text-slate-500 text-sm">

                    Serial Number

                </label>

                <p>

                    {{ $equipment->serial_number ?: '-' }}

                </p>

            </div>

        </div>

    </div>


    {{-- Description --}}
    <div class="bg-white rounded-2xl border shadow-sm">

        <div class="p-6 border-b">

            <h2 class="text-xl font-semibold">

                Description

            </h2>

        </div>

        <div class="p-6 text-slate-700">

            {{ $equipment->description ?: 'No description available.' }}

        </div>

    </div>


    <div class="bg-white rounded-2xl border shadow-sm mb-6">

    <div class="p-6 border-b">

        <h2 class="text-xl font-semibold">

            Add Maintenance Record

        </h2>

        <p class="text-slate-500 mt-1">

            Record preventive, corrective, calibration, or inspection activities.

        </p>

    </div>

    @if($errors->any())

<div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-5">

    <ul class="list-disc ml-5 text-red-600 space-y-1">

        @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif
    <form
        action="{{ route('equipment.maintenance-history.store', $equipment) }}"
        method="POST">

        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">

            {{-- Maintenance Date --}}
            <div>

                <label class="block text-sm font-medium mb-2">

                    Maintenance Date

                </label>

                <input
                    type="date"
                    name="maintenance_date"
                    value="{{ old('maintenance_date', date('Y-m-d')) }}"
                    class="w-full rounded-xl border-slate-300"
                    required>

            </div>

            {{-- Type --}}
            <div>

                <label class="block text-sm font-medium mb-2">

                    Maintenance Type

                </label>

                <select
                    name="maintenance_type"
                    class="w-full rounded-xl border-slate-300"
                    required>

                    <option value="Preventive">Preventive</option>
                    <option value="Corrective">Corrective</option>
                    <option value="Calibration">Calibration</option>
                    <option value="Inspection">Inspection</option>

                </select>

            </div>

            {{-- Work Order --}}
            <div>

                <label class="block text-sm font-medium mb-2">

                    Work Order

                </label>

                <input
                    type="text"
                    name="work_order"
                    value="{{ old('work_order') }}"
                    class="w-full rounded-xl border-slate-300">

            </div>

            {{-- Technician --}}
            <div>

                <label class="block text-sm font-medium mb-2">

                    Technician

                </label>

                <input
                    type="text"
                    name="performed_by"
                    value="{{ old('performed_by', auth()->user()->name) }}"
                    class="w-full rounded-xl border-slate-300"
                    required>

            </div>

            {{-- Result --}}
            <div>

                <label class="block text-sm font-medium mb-2">

                    Result

                </label>

                <select
                    name="result"
                    class="w-full rounded-xl border-slate-300">

                    <option value="Completed">Completed</option>
                    <option value="Pending">Pending</option>
                    <option value="Need Follow Up">Need Follow Up</option>

                </select>

            </div>

            {{-- Cost --}}
            <div>

                <label class="block text-sm font-medium mb-2">

                    Cost (Rp)

                </label>

                <input
                    type="number"
                    name="cost"
                    value="{{ old('cost',0) }}"
                    class="w-full rounded-xl border-slate-300">

            </div>

            {{-- Description --}}
            <div class="md:col-span-2">

                <label class="block text-sm font-medium mb-2">

                    Description

                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="w-full rounded-xl border-slate-300">{{ old('description') }}</textarea>

            </div>

        </div>

        <div class="border-t p-6 flex justify-end">

            <button
                type="submit"
                class="px-6 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700">

                Save Maintenance

            </button>

        </div>

    </form>

</div>

    {{-- Maintenance History --}}
<div class="bg-white rounded-2xl border shadow-sm">

    <div class="flex items-center justify-between p-6 border-b">

        <div>

            <h2 class="text-xl font-semibold">

                Maintenance History

            </h2>

            <p class="text-sm text-slate-500 mt-1">

                History of preventive, corrective, calibration and inspection activities.

            </p>

        </div>

       <form
    action="{{ route('equipment.maintenance-history.store', $equipment) }}"
    method="POST">

    @csrf

    ...
</form>

    </div>

    @if($equipment->maintenanceHistories->count())

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-5 py-4 text-left text-sm font-semibold">

                            Date

                        </th>

                        <th class="px-5 py-4 text-left text-sm font-semibold">

                            Type

                        </th>

                        <th class="px-5 py-4 text-left text-sm font-semibold">

                            Description

                        </th>

                        <th class="px-5 py-4 text-left text-sm font-semibold">

                            Technician

                        </th>

                        <th class="px-5 py-4 text-left text-sm font-semibold">

                            Result

                        </th>

                        <th class="px-5 py-4 text-center text-sm font-semibold">

                            Action

                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @foreach($equipment->maintenanceHistories as $history)

                        <tr class="hover:bg-slate-50">

                            <td class="px-5 py-4 whitespace-nowrap">

                                {{ \Carbon\Carbon::parse($history->maintenance_date)->format('d M Y') }}

                            </td>

                            <td class="px-5 py-4">

                                @php

                                    $typeColor = match($history->maintenance_type){

                                        'Preventive' => 'bg-green-100 text-green-700',

                                        'Corrective' => 'bg-red-100 text-red-700',

                                        'Calibration' => 'bg-blue-100 text-blue-700',

                                        default => 'bg-slate-100 text-slate-700',

                                    };

                                @endphp

                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $typeColor }}">

                                    {{ $history->maintenance_type }}

                                </span>

                            </td>

                            <td class="px-5 py-4">

                                {{ $history->description }}

                            </td>

                            <td class="px-5 py-4">

                                {{ $history->performed_by }}

                            </td>

                            <td class="px-5 py-4">

                                {{ $history->result }}

                            </td>

                            <td class="px-5 py-4">

                                <div class="flex justify-center gap-2">

                                    <a
                                        href="{{ route('equipment.maintenance-history.edit', [$equipment, $history]) }}"
                                        class="px-3 py-2 rounded-lg bg-yellow-100 text-yellow-700 hover:bg-yellow-200">

                                        Edit

                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('equipment.maintenance-history.destroy', [$equipment, $history]) }}"
                                        onsubmit="return confirm('Delete maintenance history?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200">

                                            Delete

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="p-12 text-center">

            <div class="text-slate-400 text-lg font-medium">

                No Maintenance History

            </div>

            <p class="text-slate-500 mt-2">

                This equipment has no maintenance record yet.

            </p>

        </div>

    @endif

</div>

    {{-- Future Module --}}
    <div class="bg-white rounded-2xl border shadow-sm">

        <div class="p-6 border-b">

            <h2 class="text-xl font-semibold">

                Future Module

            </h2>

        </div>

        <div class="grid md:grid-cols-3 gap-5 p-6">

            <div class="rounded-xl border p-5">

                Calibration History

            </div>

            <div class="rounded-xl border p-5">

                Preventive Maintenance

            </div>

            <div class="rounded-xl border p-5">

                Corrective Maintenance

            </div>

            <div class="rounded-xl border p-5">

                Work Order

            </div>

            <div class="rounded-xl border p-5">

                Spare Part

            </div>

            <div class="rounded-xl border p-5">

                Documents

            </div>

        </div>

    </div>

</div>

@endsection