@extends('layouts.app')

@section('title', 'Equipment Detail')

@section('content')

@php
    $statusBadge = match ($equipment->status) {
        'Active' => 'bg-green-100 text-green-700',
        'Standby' => 'bg-blue-100 text-blue-700',
        'Maintenance' => 'bg-yellow-100 text-yellow-700',
        'Breakdown' => 'bg-red-100 text-red-700',
        'Decommission' => 'bg-slate-200 text-slate-700',
        default => 'bg-slate-100 text-slate-700',
    };
@endphp

<div class="max-w-6xl mx-auto space-y-6">

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
            {{ session('error') }}
        </div>
    @endif


    {{-- Validation Error --}}
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-5">

            <h3 class="font-semibold text-red-700 mb-3">
                Please fix the following errors:
            </h3>

            <ul class="list-disc ml-5 text-sm text-red-600 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <div class="flex flex-wrap items-center gap-3">

                <h1 class="text-3xl font-bold text-slate-800">
                    {{ $equipment->equipment_name }}
                </h1>

                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusBadge }}">
                    {{ $equipment->status }}
                </span>

            </div>

            <p class="text-slate-500 mt-1">
                {{ $equipment->equipment_code }}
                ·
                {{ $equipment->tag_number }}
            </p>
        </div>


        <div class="flex flex-wrap gap-3">

            <a
                href="{{ route('equipment.index') }}"
                class="inline-flex items-center justify-center px-5 py-3 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition"
            >
                ← Back
            </a>

            <a
                href="{{ route('equipment.edit', $equipment) }}"
                class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-yellow-500 text-white hover:bg-yellow-600 transition"
            >
                Edit Equipment
            </a>

        </div>

    </div>


    {{-- Equipment Information --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-200">

            <h2 class="text-xl font-semibold text-slate-800">
                Equipment Information
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Master data and technical information.
            </p>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">

            {{-- Equipment Code --}}
            <div>
                <p class="text-sm text-slate-500">
                    Equipment Code
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $equipment->equipment_code }}
                </p>
            </div>


            {{-- Tag Number --}}
            <div>
                <p class="text-sm text-slate-500">
                    Tag Number
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $equipment->tag_number }}
                </p>
            </div>


            {{-- Category --}}
            <div>
                <p class="text-sm text-slate-500">
                    Category
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $equipment->category }}
                </p>
            </div>


            {{-- Location --}}
            <div>
                <p class="text-sm text-slate-500">
                    Location
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $equipment->location }}
                </p>
            </div>


            {{-- Manufacturer --}}
            <div>
                <p class="text-sm text-slate-500">
                    Manufacturer
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $equipment->manufacturer ?: '-' }}
                </p>
            </div>


            {{-- Model --}}
            <div>
                <p class="text-sm text-slate-500">
                    Model
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $equipment->model ?: '-' }}
                </p>
            </div>


            {{-- Serial Number --}}
            <div>
                <p class="text-sm text-slate-500">
                    Serial Number
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $equipment->serial_number ?: '-' }}
                </p>
            </div>


            {{-- Installation Date --}}
            <div>
                <p class="text-sm text-slate-500">
                    Installation Date
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $equipment->installation_date?->format('d M Y') ?? '-' }}
                </p>
            </div>


            {{-- Status --}}
            <div>
                <p class="text-sm text-slate-500">
                    Current Status
                </p>

                <div class="mt-2">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusBadge }}">
                        {{ $equipment->status }}
                    </span>
                </div>
            </div>

        </div>

    </div>


    {{-- Description --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-200">

            <h2 class="text-xl font-semibold text-slate-800">
                Description
            </h2>

        </div>

        <div class="p-6 text-slate-700 whitespace-pre-line">
            {{ $equipment->description ?: 'No description available.' }}
        </div>

    </div>


    {{-- Maintenance Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-sm text-slate-500">
                Total Maintenance
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ $summary['total'] }}
            </p>
        </div>


        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-sm text-slate-500">
                Preventive
            </p>

            <p class="mt-2 text-3xl font-bold text-green-600">
                {{ $summary['preventive'] }}
            </p>
        </div>


        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-sm text-slate-500">
                Corrective
            </p>

            <p class="mt-2 text-3xl font-bold text-red-600">
                {{ $summary['corrective'] }}
            </p>
        </div>


        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-sm text-slate-500">
                Inspection
            </p>

            <p class="mt-2 text-3xl font-bold text-blue-600">
                {{ $summary['inspection'] }}
            </p>
        </div>

    </div>


    {{-- Last Maintenance --}}
    @if($summary['last'])
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">

            <div class="px-6 py-5 border-b border-slate-200">

                <h2 class="text-xl font-semibold text-slate-800">
                    Latest Maintenance
                </h2>

            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">

                <div>
                    <p class="text-sm text-slate-500">
                        Date
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $summary['last']->maintenance_date?->format('d M Y') ?? '-' }}
                    </p>
                </div>


                <div>
                    <p class="text-sm text-slate-500">
                        Type
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $summary['last']->maintenance_type }}
                    </p>
                </div>


                <div>
                    <p class="text-sm text-slate-500">
                        Technician
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $summary['last']->technician }}
                    </p>
                </div>

            </div>

        </div>
    @endif


    {{-- Add Maintenance --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-200">

            <h2 class="text-xl font-semibold text-slate-800">
                Add Maintenance Record
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Record preventive, corrective, calibration, or inspection activities.
            </p>

        </div>


        <form
            action="{{ route('equipment.maintenance-history.store', $equipment) }}"
            method="POST"
        >
            @csrf

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Maintenance Date --}}
                <div>
                    <label
                        for="maintenance_date"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Maintenance Date
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="maintenance_date"
                        type="date"
                        name="maintenance_date"
                        value="{{ old('maintenance_date', date('Y-m-d')) }}"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        required
                    >
                </div>


                {{-- Maintenance Type --}}
                <div>
                    <label
                        for="maintenance_type"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Maintenance Type
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="maintenance_type"
                        name="maintenance_type"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        required
                    >
                        @foreach([
                            'Preventive',
                            'Corrective',
                            'Calibration',
                            'Inspection',
                        ] as $type)

                            <option
                                value="{{ $type }}"
                                @selected(old('maintenance_type', 'Preventive') === $type)
                            >
                                {{ $type }}
                            </option>

                        @endforeach
                    </select>
                </div>

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
                    old('status', 'Open') === $status
                )
            >
                {{ $status }}
            </option>

        @endforeach

    </select>
</div>


                {{-- Work Order Number --}}
                <div>
                    <label
                        for="work_order_number"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Work Order Number
                    </label>

                    <input
                        id="work_order_number"
                        type="text"
                        name="work_order_number"
                        value="{{ old('work_order_number') }}"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Example: WO-2026-001"
                    >
                </div>


                {{-- Technician --}}
                <div>
                    <label
                        for="technician"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Technician
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="technician"
                        type="text"
                        name="technician"
                        value="{{ old('technician', auth()->user()->name) }}"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        required
                    >
                </div>


                {{-- Duration --}}
                <div>
                    <label
                        for="duration"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Duration
                    </label>

                    <div class="relative">

                        <input
                            id="duration"
                            type="number"
                            name="duration"
                            value="{{ old('duration') }}"
                            min="0"
                            class="w-full rounded-xl border-slate-300 pr-16 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="0"
                        >

                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-400">
                            min
                        </span>

                    </div>
                </div>


                {{-- Result --}}
                <div>
                    <label
                        for="result"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Result
                    </label>

                    <select
                        id="result"
                        name="result"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">
                            Select Result
                        </option>

                        @foreach([
                            'Completed',
                            'Pending',
                            'Need Follow Up',
                        ] as $result)

                            <option
                                value="{{ $result }}"
                                @selected(old('result') === $result)
                            >
                                {{ $result }}
                            </option>

                        @endforeach
                    </select>
                </div>


                {{-- Notes --}}
                <div class="md:col-span-2">

                    <label
                        for="notes"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Notes
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                        rows="4"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Describe maintenance activity, findings, corrective action, or other notes..."
                    >{{ old('notes') }}</textarea>

                </div>

            </div>


            <div class="border-t border-slate-200 px-6 py-5 flex justify-end">

                <button
                    type="submit"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700 transition"
                >
                    Save Maintenance
                </button>

            </div>

        </form>

    </div>


    {{-- Maintenance History --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-200">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

                <div>
                    <h2 class="text-xl font-semibold text-slate-800">
                        Maintenance History
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        History of maintenance activities for this equipment.
                    </p>
                </div>

                <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-sm">
                    {{ $summary['total'] }} record(s)
                </span>

            </div>

        </div>


        @if($equipment->maintenanceHistories->count())

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-5 py-4 text-left text-sm font-semibold text-slate-600">
                                Date
                            </th>

                            <th class="px-5 py-4 text-left text-sm font-semibold text-slate-600">
                                Type
                            </th>

                            <th class="px-5 py-4 text-left text-sm font-semibold text-slate-600">
                                Work Order
                            </th>

                            <th class="px-5 py-4 text-left text-sm font-semibold text-slate-600">
                                Technician
                            </th>

                            <th class="px-5 py-4 text-left text-sm font-semibold text-slate-600">
                                Duration
                            </th>

                            <th class="px-5 py-4 text-left text-sm font-semibold text-slate-600">
                                Result
                            </th>

                            <th class="px-5 py-4 text-left text-sm font-semibold text-slate-600">
                                Notes
                            </th>

                            <th class="px-5 py-4 text-center text-sm font-semibold text-slate-600">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @foreach($equipment->maintenanceHistories as $history)

                            @php
                                $typeBadge = match ($history->maintenance_type) {
                                    'Preventive' => 'bg-green-100 text-green-700',
                                    'Corrective' => 'bg-red-100 text-red-700',
                                    'Calibration' => 'bg-blue-100 text-blue-700',
                                    'Inspection' => 'bg-purple-100 text-purple-700',
                                    default => 'bg-slate-100 text-slate-700',
                                };

                                $resultBadge = match ($history->result) {
                                    'Completed' => 'bg-green-100 text-green-700',
                                    'Pending' => 'bg-yellow-100 text-yellow-700',
                                    'Need Follow Up' => 'bg-red-100 text-red-700',
                                    default => 'bg-slate-100 text-slate-700',
                                };
                            @endphp

                            <tr class="hover:bg-slate-50 transition">

                                {{-- Date --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="text-sm text-slate-700">
                                        {{ $history->maintenance_date?->format('d M Y') ?? '-' }}
                                    </span>
                                </td>


                                {{-- Type --}}
                                <td class="px-5 py-4 whitespace-nowrap">

                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $typeBadge }}">
                                        {{ $history->maintenance_type }}
                                    </span>

                                </td>


                                {{-- Work Order --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    {{ $history->work_order_number ?: '-' }}
                                </td>


                                {{-- Technician --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    {{ $history->technician }}
                                </td>


                                {{-- Duration --}}
                                <td class="px-5 py-4 whitespace-nowrap">

                                    @if($history->duration !== null)
                                        {{ $history->duration }} min
                                    @else
                                        -
                                    @endif

                                </td>


                                {{-- Result --}}
                                <td class="px-5 py-4 whitespace-nowrap">

                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $resultBadge }}">
                                        {{ $history->result ?: 'Not Set' }}
                                    </span>

                                </td>


                                {{-- Notes --}}
                                <td class="px-5 py-4 min-w-[220px]">
                                    <span class="text-sm text-slate-600">
                                        {{ $history->notes ?: '-' }}
                                    </span>
                                </td>


                                {{-- Action --}}
                                <td class="px-5 py-4">

                                    <div class="flex justify-center gap-2">

                                        <a
                                            href="{{ route(
                                                'equipment.maintenance-history.edit',
                                                [$equipment, $history]
                                            ) }}"
                                            class="inline-flex items-center px-3 py-2 rounded-lg bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition"
                                        >
                                            Edit
                                        </a>


                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'equipment.maintenance-history.destroy',
                                                [$equipment, $history]
                                            ) }}"
                                            onsubmit="return confirm('Delete this maintenance history?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex items-center px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition"
                                            >
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


    {{-- Future Modules --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-200">

            <h2 class="text-xl font-semibold text-slate-800">
                Future Modules
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Additional equipment management modules planned for future sprints.
            </p>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 p-6">

            <div class="rounded-xl border border-dashed border-slate-300 p-5">
                <p class="font-semibold text-slate-700">
                    Calibration History
                </p>

                <p class="text-sm text-slate-500 mt-1">
                    Calibration records and schedules.
                </p>
            </div>


            <div class="rounded-xl border border-dashed border-slate-300 p-5">
                <p class="font-semibold text-slate-700">
                    Preventive Maintenance
                </p>

                <p class="text-sm text-slate-500 mt-1">
                    Preventive maintenance planning.
                </p>
            </div>


            <div class="rounded-xl border border-dashed border-slate-300 p-5">
                <p class="font-semibold text-slate-700">
                    Corrective Maintenance
                </p>

                <p class="text-sm text-slate-500 mt-1">
                    Corrective maintenance tracking.
                </p>
            </div>


            <div class="rounded-xl border border-dashed border-slate-300 p-5">
                <p class="font-semibold text-slate-700">
                    Work Order
                </p>

                <p class="text-sm text-slate-500 mt-1">
                    Work order management.
                </p>
            </div>


            <div class="rounded-xl border border-dashed border-slate-300 p-5">
                <p class="font-semibold text-slate-700">
                    Spare Part
                </p>

                <p class="text-sm text-slate-500 mt-1">
                    Spare part inventory.
                </p>
            </div>


            <div class="rounded-xl border border-dashed border-slate-300 p-5">
                <p class="font-semibold text-slate-700">
                    Documents
                </p>

                <p class="text-sm text-slate-500 mt-1">
                    Equipment manuals and documents.
                </p>
            </div>

        </div>

    </div>

</div>

@endsection