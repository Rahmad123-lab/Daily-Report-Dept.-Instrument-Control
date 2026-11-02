@extends('layouts.app')

@section('title', 'Employee Management')
@section('header', 'Employee Management')

@section('content')
<div class="space-y-6">

    {{-- Page header --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Employee Management
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Manage employee accounts and organizational information.
            </p>
        </div>

        <div class="text-sm text-slate-500">
            Total records:
            <span class="font-semibold text-slate-900">
                {{ number_format($statistics['total']) }}
            </span>
        </div>
    </div>

    {{-- Flash message --}}
    @if (session('success'))
        <div role="status"
             class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3
                    text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div role="alert"
             class="rounded-xl border border-red-200 bg-red-50 px-4 py-3
                    text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- Statistics --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        @foreach ([
            ['label' => 'Total Employees', 'value' => $statistics['total'],
             'description' => 'All registered accounts', 'color' => 'blue'],
            ['label' => 'Active', 'value' => $statistics['active'],
             'description' => 'Accounts enabled', 'color' => 'emerald'],
            ['label' => 'Pending', 'value' => $statistics['pending'],
             'description' => 'Awaiting approval', 'color' => 'amber'],
            ['label' => 'Inactive', 'value' => $statistics['inactive'],
             'description' => 'Inactive accounts', 'color' => 'slate'],
        ] as $stat)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    {{ $stat['label'] }}
                </p>

                <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                    {{ number_format($stat['value']) }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    {{ $stat['description'] }}
                </p>
            </div>
        @endforeach

    </div>

    {{-- Search and filters --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <form action="{{ route('admin.employees.index') }}"
              method="GET"
              class="grid grid-cols-1 gap-4 md:grid-cols-4">

            <div class="md:col-span-2">
                <label for="q" class="mb-2 block text-sm font-medium text-slate-700">
                    Search employee
                </label>

                <input
                    id="q"
                    name="q"
                    type="search"
                    value="{{ $search }}"
                    maxlength="255"
                    placeholder="Search NIK, employee name, or email..."
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5
                           text-sm text-slate-900 outline-none transition
                           placeholder:text-slate-400
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
            </div>

            <div>
                <label for="role" class="mb-2 block text-sm font-medium text-slate-700">
                    Role
                </label>

                <select
                    id="role"
                    name="role"
                    class="w-full rounded-xl border border-slate-300 bg-white
                           px-3 py-2.5 text-sm outline-none
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                    <option value="">All roles</option>

                    @foreach ($roles as $item)
                        <option value="{{ $item }}"
                            @selected($role === $item)>
                            {{ ucwords(str_replace('_', ' ', $item)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-slate-700">
                    Account status
                </label>

                <select
                    id="status"
                    name="status"
                    class="w-full rounded-xl border border-slate-300 bg-white
                           px-3 py-2.5 text-sm outline-none
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                    <option value="">All statuses</option>

                    @foreach ($statuses as $item)
                        <option value="{{ $item }}"
                            @selected($status === $item)>
                            {{ ucfirst($item) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-wrap gap-2 md:col-span-4">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-xl
                           bg-blue-600 px-5 py-2.5 text-sm font-semibold
                           text-white transition hover:bg-blue-700
                           focus:outline-none focus:ring-2 focus:ring-blue-500
                           focus:ring-offset-2"
                >
                    Apply filters
                </button>

                <a
                    href="{{ route('admin.employees.index') }}"
                    class="inline-flex items-center justify-center rounded-xl
                           border border-slate-300 bg-white px-5 py-2.5
                           text-sm font-semibold text-slate-700 transition
                           hover:bg-slate-50"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Employee table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-1 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-slate-900">
                Employee Directory
            </h2>

            <p class="text-xs text-slate-500">
                Showing {{ $employees->firstItem() ?? 0 }}
                – {{ $employees->lastItem() ?? 0 }}
                of {{ number_format($employees->total()) }} records
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[950px] divide-y divide-slate-200 text-left">

                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Employee
                        </th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            NIK
                        </th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Department / Position
                        </th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Role
                        </th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Status
                        </th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Registered
                        </th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
    Actions
</th>
                    </tr>
                </thead>
                

                <tbody class="divide-y divide-slate-100 bg-white">

                    @forelse ($employees as $employee)
                        @php
                            $statusClasses = match ($employee->status) {
                                'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                'inactive' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
                                default => 'bg-red-50 text-red-700 ring-red-600/20',
                            };
                        @endphp

                        <tr class="transition hover:bg-slate-50">

                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center
                                                justify-center rounded-xl bg-blue-50
                                                text-sm font-bold text-blue-700">
                                        {{ strtoupper(substr($employee->name, 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-900">
                                            {{ $employee->name }}
                                        </p>

                                        <p class="mt-0.5 text-xs text-slate-500">
                                            {{ $employee->email ?: 'No email registered' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="font-mono text-sm text-slate-700">
                                    {{ $employee->nik }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                <p class="text-sm font-medium text-slate-800">
                                    {{ $employee->department ?: '—' }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $employee->position ?: 'Position not set' }}
                                </p>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="text-sm font-medium text-slate-700">
                                    {{ ucwords(str_replace('_', ' ', $employee->role)) }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="inline-flex items-center rounded-full
                                             px-2.5 py-1 text-xs font-semibold
                                             ring-1 ring-inset {{ $statusClasses }}">
                                    {{ ucfirst($employee->status) }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500">
                                {{ $employee->created_at?->format('d M Y') ?? '—' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
    <div class="flex items-center gap-2">
        {{-- View employee details --}}
        <a
            href="{{ route('admin.employees.show', $employee) }}"
            class="inline-flex items-center rounded-lg border border-slate-300
                   px-3 py-2 text-xs font-semibold text-slate-700
                   transition hover:bg-slate-50"
        >
            Details
        </a>

        {{-- Edit employee --}}
        <a
            href="{{ route('admin.employees.edit', $employee) }}"
            class="inline-flex items-center rounded-lg bg-blue-50
                   px-3 py-2 text-xs font-semibold text-blue-700
                   transition hover:bg-blue-100"
        >
            Edit
        </a>
    </div>
</td>
                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center
                                            justify-center rounded-2xl bg-slate-100">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor"
                                         stroke-width="1.7" stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="text-slate-400">
                                        <circle cx="11" cy="11" r="8"/>
                                        <path d="m21 21-4.35-4.35"/>
                                    </svg>
                                </div>

                                <p class="mt-4 font-semibold text-slate-800">
                                    No employees found
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Try adjusting your search or filters.
                                </p>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($employees->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $employees->links() }}
            </div>
        @endif

    </div>
</div>
@endsection