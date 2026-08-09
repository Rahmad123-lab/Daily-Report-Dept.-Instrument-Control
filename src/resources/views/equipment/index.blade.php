@extends('layouts.app')

@section('title', 'Equipment Master')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Equipment Master
            </h1>

            <p class="text-slate-500">
                Manage instrument and equipment master data.
            </p>
        </div>

        <a href="{{ route('equipment.create') }}"
            class="inline-flex items-center px-5 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition">

            + Add Equipment

        </a>

    </div>

    {{-- Success --}}
    @if(session('success'))

        <div class="rounded-xl border border-green-200 bg-green-100 px-5 py-4 text-green-700">

            {{ session('success') }}

        </div>

    @endif
    @if(session('error'))

<div class="rounded-xl border border-red-200 bg-red-100 px-5 py-4 text-red-700">

    {{ session('error') }}

</div>

@endif

    {{-- Summary Cards --}}

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">

    <div class="bg-white rounded-2xl border shadow-sm p-6">

        <p class="text-sm text-slate-500">
            Total Equipment
        </p>

        <h2 class="mt-2 text-3xl font-bold text-slate-800">
            {{ $stats['total'] }}
        </h2>

        <p class="mt-3 text-sm text-slate-500">
            Registered equipment
        </p>

    </div>


    <div class="bg-white rounded-2xl border shadow-sm p-6">

        <p class="text-sm text-slate-500">
            Active
        </p>

        <h2 class="mt-2 text-3xl font-bold text-green-600">
            {{ $stats['active'] }}
        </h2>

        <span class="inline-flex mt-3 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">

            Operational

        </span>

    </div>


    <div class="bg-white rounded-2xl border shadow-sm p-6">

        <p class="text-sm text-slate-500">
            Standby
        </p>

        <h2 class="mt-2 text-3xl font-bold text-yellow-600">
            {{ $stats['standby'] }}
        </h2>

        <span class="inline-flex mt-3 px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-medium">

            Scheduled

        </span>

    </div>


    <div class="bg-white rounded-2xl border shadow-sm p-6">

        <p class="text-sm text-slate-500">
            Maintenance
        </p>

        <h2 class="mt-2 text-3xl font-bold text-red-600">
            {{ $stats['maintenance'] }}
        </h2>

        <span class="inline-flex mt-3 px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-medium">

            Maintenance

        </span>

    </div>

</div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border shadow-sm p-5">

        <form method="GET">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search equipment..."
                    class="rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500">

                <select
                    name="category"
                    class="rounded-xl border-slate-300">

                    <option value="">All Category</option>

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

                <select
                    name="status"
                    class="rounded-xl border-slate-300">

                    <option value="">All Status</option>

                    <option value="Active" @selected(request('status')=='Active')>
                        Active
                    </option>

                    <option value="Standby" @selected(request('status')=='Standby')>
                        Standby
                    </option>

                    <option value="Maintenance" @selected(request('status')=='Maintenance')>
                        Maintenance
                    </option>

                    <option value="Breakdown" @selected(request('status')=='Breakdown')>
                        Breakdown
                    </option>

                    <option value="Decommission" @selected(request('status')=='Decommission')>
                        Decommission
                    </option>

                </select>

                <div class="flex gap-3">

    <button
        type="submit"
        class="flex-1 rounded-xl bg-slate-800 text-white hover:bg-slate-900 transition">

        Search

    </button>

    <a
        href="{{ route('equipment.index') }}"
        class="px-5 py-3 rounded-xl border hover:bg-slate-50">

        Reset

    </a>

</div>

            </div>

        </form>

    </div>


    {{-- Table --}}
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-5 py-4 text-left text-sm font-semibold text-slate-600">
                            Code
                        </th>

                        <th class="px-5 py-4 text-left text-sm font-semibold text-slate-600">
                            Tag Number
                        </th>

                        <th class="px-5 py-4 text-left text-sm font-semibold text-slate-600">
                            Equipment
                        </th>

                        <th class="px-5 py-4 text-left text-sm font-semibold text-slate-600">
                            Category
                        </th>

                        <th class="px-5 py-4 text-left text-sm font-semibold text-slate-600">
                            Location
                        </th>

                        <th class="px-5 py-4 text-left text-sm font-semibold text-slate-600">
                            Status
                        </th>

                        <th class="px-5 py-4 text-center text-sm font-semibold text-slate-600">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

@forelse($equipment as $item)

<tr>

    <td class="px-5 py-4">{{ $item->equipment_code }}</td>

    <td class="px-5 py-4">{{ $item->tag_number }}</td>

    <td class="px-5 py-4 font-medium">
        {{ $item->equipment_name }}
    </td>

    <td class="px-5 py-4">
        {{ $item->category }}
    </td>

    <td class="px-5 py-4">
        {{ $item->location }}
    </td>

    <td class="px-5 py-4">

    @php
        $badge = match($item->status) {

            'Active' => 'bg-green-100 text-green-700',

            'Standby' => 'bg-blue-100 text-blue-700',

            'Maintenance' => 'bg-yellow-100 text-yellow-700',

            'Breakdown' => 'bg-red-100 text-red-700',

            'Decommission' => 'bg-gray-100 text-gray-700',

            default => 'bg-slate-100 text-slate-700',

        };
    @endphp

    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $badge }}">
        {{ $item->status }}
    </span>

</td>

    <td class="px-5 py-4">

        <div class="flex justify-center gap-2">

            <a href="{{ route('equipment.show',$item) }}"
               class="text-blue-600 hover:underline">
                View
            </a>

            <a href="{{ route('equipment.edit',$item) }}"
               class="text-yellow-600 hover:underline">
                Edit
            </a>

            <form
                action="{{ route('equipment.destroy',$item) }}"
                method="POST"
                onsubmit="return confirm('Delete this equipment?')">

                @csrf
                @method('DELETE')

                <button
                    class="text-red-600 hover:underline">

                    Delete

                </button>

            </form>

        </div>

    </td>

</tr>

@empty

<tr>

    <td
        colspan="7"
        class="px-5 py-12 text-center text-slate-500">

        No equipment data available.

    </td>

</tr>

@endforelse

</tbody>

            </table>

        </div>

        @if($equipment->hasPages())

            <div class="border-t p-5">

                {{ $equipment->links() }}

            </div>

        @endif

    </div>

</div>

@endsection