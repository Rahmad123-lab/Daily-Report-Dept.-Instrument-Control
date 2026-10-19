@extends('layouts.app')

@section('title', 'Add Maintenance')

@section('header', 'Add Maintenance')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- ================================================================
        PAGE HEADER
    ================================================================= --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Add Maintenance
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Tambahkan riwayat maintenance untuk equipment.
            </p>
        </div>

        <a
            href="{{ route('maintenance.index') }}"
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
            <span>Back to Maintenance</span>
        </a>

    </div>


    {{-- ================================================================
        VALIDATION ERROR
    ================================================================= --}}
    @if ($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <div class="flex gap-3">

                <div class="text-red-500 text-lg">
                    ⚠️
                </div>

                <div>

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
        FORM
    ================================================================= --}}
    <form
        method="POST"
        id="maintenance-form"
        class="space-y-6"
    >

        @csrf


        {{-- ============================================================
            EQUIPMENT
        ============================================================= --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-200">

                <h2 class="text-lg font-semibold text-slate-800">
                    Equipment Information
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Pilih equipment yang akan dicatat aktivitas maintenance-nya.
                </p>

            </div>


            <div class="p-6">

                <label
                    for="equipment_id"
                    class="block text-sm font-medium text-slate-700"
                >
                    Equipment <span class="text-red-500">*</span>
                </label>

                <select
                    id="equipment_id"
                    name="equipment_id"
                    required
                    class="mt-2 block w-full rounded-xl border-slate-300
                           focus:border-blue-500 focus:ring-blue-500
                           text-sm"
                >

                    <option value="">
                        -- Select Equipment --
                    </option>

                    @forelse ($equipment as $item)

                        <option
                            value="{{ $item->id }}"
                            data-equipment-url="{{ route('equipment.show', $item) }}"
                            {{ old('equipment_id') == $item->id ? 'selected' : '' }}
                        >
                            {{ $item->equipment_code }}
                            -
                            {{ $item->equipment_name }}

                            @if ($item->tag_number)
                                ({{ $item->tag_number }})
                            @endif
                        </option>

                    @empty

                        <option value="" disabled>
                            No equipment available
                        </option>

                    @endforelse

                </select>

                @error('equipment_id')

                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

                <p class="mt-2 text-xs text-slate-500">
                    Equipment harus dipilih sebelum maintenance dapat disimpan.
                </p>

            </div>

        </div>


        {{-- ============================================================
            MAINTENANCE INFORMATION
        ============================================================= --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-200">

                <h2 class="text-lg font-semibold text-slate-800">
                    Maintenance Information
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Masukkan informasi utama aktivitas maintenance.
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
                            value="{{ old('maintenance_date', now()->format('Y-m-d')) }}"
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

                           
                        @foreach([
                            'Preventive',
                            'Corrective',
                            'Calibration',
                            'Inspection',
                            'Breakdown',
                        ] as $type)
                            <option
                                value="{{ $type }}"
                                @selected(old('maintenance_type') === $type)
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

                            <option value="">
                                -- Select Status --
                            </option>

                            @foreach ([
                                'Open',
                                'Progress',
                                'Completed',
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ old('status', 'Open') === $status ? 'selected' : '' }}
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


                    {{-- Work Order --}}
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
                            value="{{ old('work_order_number') }}"
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
                            value="{{ old('technician') }}"
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
                                value="{{ old('duration') }}"
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
                @selected(old('result', 'Pending') === $result)
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
                    >{{ old('notes') }}</textarea>

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
        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">

            <a
                href="{{ route('maintenance.index') }}"
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

            <button
                type="submit"
                id="save-maintenance"
                disabled
                class="inline-flex items-center justify-center gap-2
                       px-5 py-2.5
                       bg-blue-600
                       text-white
                       text-sm font-semibold
                       rounded-xl
                       hover:bg-blue-700
                       disabled:opacity-50
                       disabled:cursor-not-allowed
                       transition"
            >
                <span>✓</span>
                <span>Save Maintenance</span>
            </button>

        </div>

    </form>

</div>


{{-- ================================================================
    FORM ACTION SCRIPT
================================================================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const equipmentSelect = document.getElementById('equipment_id');
        const form = document.getElementById('maintenance-form');
        const submitButton = document.getElementById('save-maintenance');

        function updateFormAction() {

            const selectedOption =
                equipmentSelect.options[
                    equipmentSelect.selectedIndex
                ];

            const equipmentId =
                equipmentSelect.value;

            const equipmentUrl =
                selectedOption?.dataset?.equipmentUrl;

            if (!equipmentId || !equipmentUrl) {

                form.action = '';
                submitButton.disabled = true;

                return;
            }

            /*
             * Build the Maintenance History store endpoint.
             *
             * Current route:
             * POST /equipment/{equipment}/maintenance-history
             */
            form.action =
                `/equipment/${equipmentId}/maintenance-history`;

            submitButton.disabled = false;
        }


        equipmentSelect.addEventListener(
            'change',
            updateFormAction
        );


        updateFormAction();

    });
</script>

@endsection