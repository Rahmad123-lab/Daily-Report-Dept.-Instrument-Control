@extends('layouts.app')

@section('title', 'Edit Maintenance History')

@section('header', 'Edit Maintenance History')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- ================================================================
        PAGE HEADER
    ================================================================= --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Edit Maintenance History
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Perbarui informasi aktivitas maintenance equipment.
            </p>
        </div>

        <a
            href="{{ route('equipment.show', $equipment) }}"
            class="inline-flex items-center justify-center gap-2
                   px-4 py-2.5
                   bg-white
                   border border-slate-200
                   text-slate-700
                   text-sm font-medium
                   rounded-xl
                   hover:bg-slate-50
                   transition"
        >
            <span>←</span>
            <span>Back to Equipment</span>
        </a>

    </div>


    {{-- ================================================================
        VALIDATION ERRORS
    ================================================================= --}}
    @if ($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <div class="flex gap-3">

                <div class="text-red-500 text-lg">
                    ⚠️
                </div>

                <div class="min-w-0">

                    <h3 class="text-sm font-semibold text-red-800">
                        Please check the following errors:
                    </h3>

                    <ul class="mt-2 list-disc list-inside space-y-1 text-sm text-red-700">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- ================================================================
        EQUIPMENT INFORMATION
    ================================================================= --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-200">

            <h2 class="text-lg font-semibold text-slate-800">
                Equipment Information
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Equipment terkait maintenance history ini.
            </p>

        </div>


        <div class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- Equipment Code --}}
                <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Equipment Code
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-800">
                        {{ $equipment->equipment_code ?: '-' }}
                    </p>

                </div>


                {{-- Equipment Name --}}
                <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Equipment Name
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-800">
                        {{ $equipment->equipment_name ?: '-' }}
                    </p>

                </div>


                {{-- Tag Number --}}
                <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Tag Number
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-800">
                        {{ $equipment->tag_number ?: '-' }}
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
        MAINTENANCE FORM
    ================================================================= --}}
    <form
        method="POST"
        action="{{ route(
            'equipment.maintenance-history.update',
            [
                $equipment,
                $history
            ]
        ) }}"
        class="space-y-6"
    >

        @csrf

        @method('PUT')


        {{-- ============================================================
            MAINTENANCE INFORMATION
        ============================================================= --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-200">

                <h2 class="text-lg font-semibold text-slate-800">
                    Maintenance Information
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Perbarui detail aktivitas maintenance.
                </p>

            </div>


            <div class="p-6 space-y-6">

                {{-- DATE + TYPE --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Maintenance Date --}}
                    <div>

                        <label
                            for="maintenance_date"
                            class="block text-sm font-medium text-slate-700"
                        >
                            Maintenance Date
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="date"
                            id="maintenance_date"
                            name="maintenance_date"
                            value="{{ old(
                                'maintenance_date',
                                optional($history->maintenance_date)->format('Y-m-d')
                            ) }}"
                            required
                            class="mt-2 block w-full rounded-xl border-slate-300
                                   focus:border-blue-500 focus:ring-blue-500
                                   text-sm"
                        >

                        @error('maintenance_date')

                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- Maintenance Type --}}
                    <div>

                        <label
                            for="maintenance_type"
                            class="block text-sm font-medium text-slate-700"
                        >
                            Maintenance Type
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="maintenance_type"
                            name="maintenance_type"
                            required
                            class="mt-2 block w-full rounded-xl border-slate-300
                                   focus:border-blue-500 focus:ring-blue-500
                                   text-sm"
                        >

                            <option value="">
                                -- Select Type --
                            </option>

                            @foreach ([
                                'Preventive',
                                'Corrective',
                                'Calibration',
                                'Inspection',
                                'Breakdown',
                            ] as $type)
f
                                <option
                                    value="{{ $type }}"
                                    {{ old(
                                        'maintenance_type',
                                        $history->maintenance_type
                                    ) === $type ? 'selected' : '' }}
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                        @error('maintenance_type')

                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>


                {{-- STATUS + WORK ORDER --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Status --}}
                    <div>

                        <label
                            for="status"
                            class="block text-sm font-medium text-slate-700"
                        >
                            Status
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="status"
                            name="status"
                            required
                            class="mt-2 block w-full rounded-xl border-slate-300
                                   focus:border-blue-500 focus:ring-blue-500
                                   text-sm"
                        >

                            @foreach ([
                                'Open',
                                'Progress',
                                'Completed',
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old(
                                        'status',
                                        $history->status
                                    ) === $status ? 'selected' : '' }}
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                        @error('status')

                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- Work Order Number --}}
                    <div>

                        <label
                            for="work_order_number"
                            class="block text-sm font-medium text-slate-700"
                        >
                            Work Order Number
                        </label>

                        <input
                            type="text"
                            id="work_order_number"
                            name="work_order_number"
                            value="{{ old(
                                'work_order_number',
                                $history->work_order_number
                            ) }}"
                            maxlength="100"
                            placeholder="Example: WO-I&C-2026-001"
                            class="mt-2 block w-full rounded-xl border-slate-300
                                   focus:border-blue-500 focus:ring-blue-500
                                   text-sm"
                        >

                        @error('work_order_number')

                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>


                {{-- TECHNICIAN + DURATION --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Technician --}}
                    <div>

                        <label
                            for="technician"
                            class="block text-sm font-medium text-slate-700"
                        >
                            Technician
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            id="technician"
                            name="technician"
                            value="{{ old(
                                'technician',
                                $history->technician
                            ) }}"
                            maxlength="255"
                            required
                            placeholder="Technician name"
                            class="mt-2 block w-full rounded-xl border-slate-300
                                   focus:border-blue-500 focus:ring-blue-500
                                   text-sm"
                        >

                        @error('technician')

                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- Duration --}}
                    <div>

                        <label
                            for="duration"
                            class="block text-sm font-medium text-slate-700"
                        >
                            Duration
                        </label>

                        <div class="mt-2 flex rounded-xl shadow-sm">

                            <input
                                type="number"
                                id="duration"
                                name="duration"
                                value="{{ old(
                                    'duration',
                                    $history->duration
                                ) }}"
                                min="0"
                                step="1"
                                placeholder="0"
                                class="block w-full rounded-l-xl border-slate-300
                                       focus:border-blue-500 focus:ring-blue-500
                                       text-sm"
                            >

                            <span
                                class="inline-flex items-center px-4
                                       rounded-r-xl
                                       border border-l-0 border-slate-300
                                       bg-slate-50
                                       text-sm text-slate-500"
                            >
                                minutes
                            </span>

                        </div>

                        @error('duration')

                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>


               
{{-- RESULT --}}
<div>
    <label
        for="result"
        class="block text-sm font-medium text-slate-700"
    >
        Result
    </label>

    <select
        id="result"
        name="result"
        class="mt-2 block w-full rounded-xl border-slate-300
               focus:border-blue-500 focus:ring-blue-500
               text-sm"
    >
        @foreach([
            'Pending',
            'Completed',
            'Failed',
        ] as $result)
            <option
                value="{{ $result }}"
                @selected(old('result', $history->result ?? 'Pending') === $result)
            >
                {{ $result }}
            </option>
        @endforeach
    </select>

    @error('result')
        <p class="mt-2 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror
</div>



                {{-- NOTES --}}
                <div>

                    <label
                        for="notes"
                        class="block text-sm font-medium text-slate-700"
                    >
                        Notes
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                        rows="5"
                        placeholder="Additional maintenance notes..."
                        class="mt-2 block w-full rounded-xl border-slate-300
                               focus:border-blue-500 focus:ring-blue-500
                               text-sm resize-y"
                    >{{ old('notes', $history->notes) }}</textarea>

                    @error('notes')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>

        </div>


        {{-- ============================================================
            ACTIONS
        ============================================================= --}}
        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">

            <div>

                <a
                    href="{{ route('equipment.show', $equipment) }}"
                    class="inline-flex items-center justify-center
                           px-5 py-2.5
                           bg-white
                           border border-slate-200
                           text-slate-700
                           text-sm font-medium
                           rounded-xl
                           hover:bg-slate-50
                           transition"
                >
                    Cancel
                </a>

            </div>


            <div class="flex flex-col-reverse sm:flex-row gap-3">

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2
                           px-5 py-2.5
                           bg-blue-600
                           text-white
                           text-sm font-semibold
                           rounded-xl
                           hover:bg-blue-700
                           focus:outline-none
                           focus:ring-2
                           focus:ring-blue-500
                           focus:ring-offset-2
                           transition"
                >
                    <span>✓</span>
                    <span>Update Maintenance</span>
                </button>

            </div>

        </div>

    </form>


    {{-- ================================================================
        DANGER ZONE
    ================================================================= --}}
    <div class="bg-white rounded-2xl border border-red-200 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-red-100 bg-red-50">

            <h2 class="text-lg font-semibold text-red-800">
                Danger Zone
            </h2>

            <p class="mt-1 text-sm text-red-600">
                Menghapus maintenance history akan menghilangkan data secara permanen.
            </p>

        </div>


        <div class="p-6">

            <form
                method="POST"
                action="{{ route(
                    'equipment.maintenance-history.destroy',
                    [
                        $equipment,
                        $history
                    ]
                ) }}"
                onsubmit="return confirm(
                    'Apakah Anda yakin ingin menghapus maintenance history ini? Data yang dihapus tidak dapat dikembalikan.'
                );"
            >

                @csrf

                @method('DELETE')

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2
                           px-4 py-2.5
                           bg-red-600
                           text-white
                           text-sm font-semibold
                           rounded-xl
                           hover:bg-red-700
                           focus:outline-none
                           focus:ring-2
                           focus:ring-red-500
                           focus:ring-offset-2
                           transition"
                >
                    <span>🗑</span>
                    <span>Delete Maintenance History</span>
                </button>

            </form>

        </div>

    </div>

</div>

@endsection