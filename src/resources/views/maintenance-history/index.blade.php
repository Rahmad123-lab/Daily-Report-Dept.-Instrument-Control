
@extends('layouts.app')

@section('title', 'Maintenance')
@section('header', 'Maintenance')

@section('content')
<div class="space-y-6">

    {{-- =====================================================
        PAGE HEADER
    ====================================================== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                Maintenance
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Monitor dan kelola aktivitas maintenance equipment.
            </p>
        </div>

        <a
            href="{{ route('maintenance.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Maintenance
        </a>
    </div>

    {{-- =====================================================
        STATUS SUMMARY
    ====================================================== --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Total --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Total Maintenance
                    </p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $stats['total'] ?? 0 }}
                    </p>
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M9 5h6m-8 4h10m-10 4h10m-10 4h6"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Open --}}
        <div class="rounded-2xl border border-amber-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Open</p>
                    <p class="mt-2 text-3xl font-bold text-amber-600">
                        {{ $stats['open'] ?? 0 }}
                    </p>
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="8" stroke-width="1.8"/>
                        <path stroke-linecap="round" stroke-width="1.8"
                            d="M12 8v4l2.5 1.5"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- In Progress --}}
        <div class="rounded-2xl border border-blue-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">In Progress</p>
                    <p class="mt-2 text-3xl font-bold text-blue-600">
                        {{ $stats['progress'] ?? 0 }}
                    </p>
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="8" stroke-width="1.8"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.8" d="M12 7v5l3 2"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Completed --}}
        <div class="rounded-2xl border border-emerald-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Completed</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-600">
                        {{ $stats['completed'] ?? 0 }}
                    </p>
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="m7 12 3 3 7-7"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- =====================================================
        MAINTENANCE TYPE
    ====================================================== --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
            <h2 class="text-lg font-semibold text-slate-800">
                Maintenance Type
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Distribusi berdasarkan jenis maintenance.
            </p>
        </div>

        <div class="grid grid-cols-2 gap-4 p-5 sm:grid-cols-3 lg:grid-cols-5 sm:p-6">

            @php
                $maintenanceTypes = [
                    [
                        'label' => 'Preventive',
                        'key' => 'preventive',
                        'style' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                    ],
                    [
                        'label' => 'Corrective',
                        'key' => 'corrective',
                        'style' => 'border-amber-200 bg-amber-50 text-amber-700',
                    ],
                    [
                        'label' => 'Calibration',
                        'key' => 'calibration',
                        'style' => 'border-blue-200 bg-blue-50 text-blue-700',
                    ],
                    [
                        'label' => 'Inspection',
                        'key' => 'inspection',
                        'style' => 'border-slate-200 bg-slate-50 text-slate-700',
                    ],
                    [
                        'label' => 'Breakdown',
                        'key' => 'breakdown',
                        'style' => 'border-red-200 bg-red-50 text-red-700',
                    ],
                ];
            @endphp

            @foreach ($maintenanceTypes as $type)
                <div class="rounded-xl border border-slate-200 bg-white p-4 transition hover:border-slate-300 hover:shadow-sm">
                    <p class="text-xs font-medium text-slate-500">
                        {{ $type['label'] }}
                    </p>
                    <p class="mt-2 text-2xl font-bold text-slate-800">
                        {{ $stats[$type['key']] ?? 0 }}
                    </p>
                    <span class="mt-3 inline-flex rounded-lg border px-2 py-1 text-xs font-medium {{ $type['style'] }}">
                        {{ $type['label'] }}
                    </span>
                </div>
            @endforeach

        </div>
    </section>

    {{-- =====================================================
        FILTERS
    ====================================================== --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Filter Maintenance
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Cari data berdasarkan equipment, status, atau jenis pekerjaan.
            </p>
        </div>

        <form
            method="GET"
            action="{{ route('maintenance.index') }}"
            class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4"
        >
            {{-- Search --}}
            <div class="xl:col-span-2">
                <label for="search" class="mb-2 block text-sm font-medium text-slate-700">
                    Search
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" stroke-width="1.8"/>
                            <path stroke-linecap="round" stroke-width="1.8"
                                d="m16 16 4 4"/>
                        </svg>
                    </div>
                    <input
                        id="search"
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Equipment, code, tag, work order..."
                        class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-slate-700">
                    Status
                </label>
                <select
                    id="status"
                    name="status"
                    class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                    <option value="">All Status</option>
                    @foreach (['Open', 'Progress', 'Completed'] as $status)
                        <option
                            value="{{ $status }}"
                            @selected(request('status') === $status)
                        >
                            {{ $status === 'Progress' ? 'In Progress' : $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Type --}}
            <div>
                <label for="maintenance_type" class="mb-2 block text-sm font-medium text-slate-700">
                    Maintenance Type
                </label>
                <select
                    id="maintenance_type"
                    name="maintenance_type"
                    class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                    <option value="">All Types</option>
                    @foreach (['Preventive', 'Corrective', 'Calibration', 'Inspection', 'Breakdown'] as $type)
                        <option
                            value="{{ $type }}"
                            @selected(request('maintenance_type') === $type)
                        >
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter actions --}}
            <div class="flex flex-col gap-2 sm:flex-row md:col-span-2 xl:col-span-4 xl:justify-end">
                <a
                    href="{{ route('maintenance.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                    Reset Filter
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M4 5h16M7 12h10m-7 7h4"/>
                    </svg>
                    Apply Filter
                </button>
            </div>
        </form>
    </section>

    {{-- =====================================================
        MAINTENANCE HISTORY TABLE
    ====================================================== --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    Maintenance History
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Daftar aktivitas maintenance equipment.
                </p>
            </div>

            <span class="inline-flex w-fit items-center rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                {{ method_exists($maintenance, 'total') ? $maintenance->total() : $maintenance->count() }} Records
            </span>
        </div>

        {{-- Active filter indicators --}}
        @if (request()->filled('search') || request()->filled('status') || request()->filled('maintenance_type'))
            <div class="flex flex-wrap items-center gap-2 border-b border-slate-100 px-5 py-3 sm:px-6">
                <span class="text-xs font-medium text-slate-500">
                    Active filters:
                </span>

                @if (request()->filled('search'))
                    <span class="rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
                        Search: {{ request('search') }}
                    </span>
                @endif

                @if (request()->filled('status'))
                    <span class="rounded-lg bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                        Status: {{ request('status') === 'Progress' ? 'In Progress' : request('status') }}
                    </span>
                @endif

                @if (request()->filled('maintenance_type'))
                    <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">
                        Type: {{ request('maintenance_type') }}
                    </span>
                @endif

                <a
                    href="{{ route('maintenance.index') }}"
                    class="ml-auto text-xs font-semibold text-blue-600 hover:text-blue-800"
                >
                    Clear all
                </a>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">

                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <th scope="col" class="px-6 py-4">Date / WO</th>
                        <th scope="col" class="px-6 py-4">Equipment</th>
                        <th scope="col" class="px-6 py-4">Type</th>
                        <th scope="col" class="px-6 py-4">Technician</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse ($maintenance as $item)
                        @php
                            $typeClass = match ($item->maintenance_type) {
                                'Preventive'  => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                'Corrective'  => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                'Calibration' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                'Inspection'  => 'bg-slate-100 text-slate-700 ring-slate-500/20',
                                'Breakdown'   => 'bg-red-50 text-red-700 ring-red-600/20',
                                default       => 'bg-slate-100 text-slate-600 ring-slate-500/20',
                            };

                            $statusClass = match ($item->status) {
                                'Open'      => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                'Progress'  => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                'Completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                default     => 'bg-slate-100 text-slate-600 ring-slate-500/20',
                            };
                        @endphp

                        <tr class="transition hover:bg-slate-50/80">

                            {{-- Date / Work Order --}}
                            <td class="whitespace-nowrap px-6 py-4">
                                <p class="font-semibold text-slate-800">
                                    @if ($item->maintenance_date)
                                        {{ \Illuminate\Support\Carbon::parse($item->maintenance_date)->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </p>

                                @if ($item->work_order_number)
                                    <p class="mt-1 text-xs text-slate-400">
                                        WO: {{ $item->work_order_number }}
                                    </p>
                                @else
                                    <p class="mt-1 text-xs text-slate-400">
                                        No work order
                                    </p>
                                @endif
                            </td>

                            {{-- Equipment --}}
                            <td class="px-6 py-4">
                                @if ($item->equipment)
                                    <a
                                        href="{{ route('equipment.show', $item->equipment) }}"
                                        class="font-semibold text-slate-800 transition hover:text-blue-600"
                                    >
                                        {{ $item->equipment->equipment_name ?: 'Unnamed Equipment' }}
                                    </a>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $item->equipment->equipment_code ?: '-' }}

                                        @if ($item->equipment->tag_number)
                                            <span class="px-1">·</span>
                                            {{ $item->equipment->tag_number }}
                                        @endif
                                    </p>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-red-500">
                                        <svg class="h-4 w-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            aria-hidden="true">
                                            <circle cx="12" cy="12" r="9" stroke-width="1.8"/>
                                            <path stroke-linecap="round" stroke-width="1.8"
                                                d="M12 8v4m0 4h.01"/>
                                        </svg>
                                        Equipment not found
                                    </span>
                                @endif
                            </td>

                            {{-- Maintenance Type --}}
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $typeClass }}">
                                    {{ $item->maintenance_type ?: '-' }}
                                </span>
                            </td>

                            {{-- Technician --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-600">
                                        @if ($item->technician)
                                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(trim($item->technician), 0, 1)) }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                    <span class="text-slate-600">
                                        {{ $item->technician ?: '-' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusClass }}">
                                    <span class="h-1.5 w-1.5 rounded-full
                                        {{ $item->status === 'Open' ? 'bg-amber-500' : '' }}
                                        {{ $item->status === 'Progress' ? 'bg-blue-500' : '' }}
                                        {{ $item->status === 'Completed' ? 'bg-emerald-500' : '' }}
                                        {{ !in_array($item->status, ['Open', 'Progress', 'Completed'], true) ? 'bg-slate-400' : '' }}">
                                    </span>
                                    {{ $item->status === 'Progress' ? 'In Progress' : ($item->status ?: '-') }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                @if ($item->equipment)
                                    <a
                                        href="{{ route('equipment.maintenance-history.edit', [$item->equipment, $item]) }}"
                                        aria-label="Edit maintenance history {{ $item->id }}"
                                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    >
                                        <svg class="h-4 w-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.8"
                                                d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5M18.5 3.5a2.121 2.121 0 0 1 3 3L12 16l-4 1 1-4 9.5-9.5Z"/>
                                        </svg>
                                        Edit
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">
                                        Unavailable
                                    </span>
                                @endif
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                        <svg class="h-7 w-7" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.8"
                                                d="M9 5h6m-8 4h10m-10 4h10m-10 4h6"/>
                                        </svg>
                                    </div>

                                    <h3 class="mt-4 text-sm font-semibold text-slate-800">
                                        No maintenance history
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        @if (request()->filled('search') || request()->filled('status') || request()->filled('maintenance_type'))
                                            Tidak ada data yang cocok dengan filter yang dipilih.
                                        @else
                                            Belum ada data maintenance. Tambahkan data maintenance pertama.
                                        @endif
                                    </p>

                                    @if (request()->filled('search') || request()->filled('status') || request()->filled('maintenance_type'))
                                        <a
                                            href="{{ route('maintenance.index') }}"
                                            class="mt-4 text-sm font-semibold text-blue-600 hover:text-blue-800"
                                        >
                                            Clear filters
                                        </a>
                                    @else
                                        <a
                                            href="{{ route('maintenance.create') }}"
                                            class="mt-4 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                                        >
                                            <svg class="h-4 w-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Add Maintenance
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if (method_exists($maintenance, 'hasPages') && $maintenance->hasPages())
            <div class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <p class="text-xs text-slate-500">
                    Showing
                    <span class="font-semibold text-slate-700">
                        {{ $maintenance->firstItem() ?? 0 }}
                    </span>
                    to
                    <span class="font-semibold text-slate-700">
                        {{ $maintenance->lastItem() ?? 0 }}
                    </span>
                    of
                    <span class="font-semibold text-slate-700">
                        {{ $maintenance->total() }}
                    </span>
                    records
                </p>

                <div class="maintenance-pagination">
                    {{ $maintenance->withQueryString()->links() }}
                </div>
            </div>
        @endif

    </section>

</div>
@endsection
