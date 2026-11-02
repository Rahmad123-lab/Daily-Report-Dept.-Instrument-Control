
@extends('layouts.app')

@section('title', 'Employee Details')
@section('header', 'Employee Details')

@section('content')
<div class="mx-auto max-w-6xl space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">
            Dashboard
        </a>
        <span>/</span>
        <a href="{{ route('admin.employees.index') }}" class="hover:text-blue-600">
            Employee Management
        </a>
        <span>/</span>
        <span class="font-medium text-slate-800">Employee Details</span>
    </nav>

    {{-- Flash message --}}
    @if (session('success'))
        <div role="status"
             class="rounded-xl border border-emerald-200 bg-emerald-50
                    px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation or operation errors --}}
    @if (session('error'))
        <div role="alert"
             class="rounded-xl border border-red-200 bg-red-50
                    px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- Page header --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Employee Details
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                View employee identity, organizational information, and account status.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.employees.index') }}"
               class="inline-flex items-center justify-center rounded-xl
                      border border-slate-300 bg-white px-4 py-2.5
                      text-sm font-semibold text-slate-700 transition
                      hover:bg-slate-50">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="18" height="18" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round"
                     class="mr-2">
                    <path d="m15 18-6-6 6-6"/>
                    <path d="M20 12H9"/>
                </svg>
                Back to Employees
            </a>

            <a href="{{ route('admin.employees.edit', $employee) }}"
               class="inline-flex items-center justify-center rounded-xl
                      bg-blue-600 px-4 py-2.5 text-sm font-semibold
                      text-white shadow-sm transition hover:bg-blue-700
                      focus:outline-none focus:ring-2 focus:ring-blue-500
                      focus:ring-offset-2">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="18" height="18" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round"
                     class="mr-2">
                    <path d="M12 20h9"/>
                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L9 17l-4 1 1-4Z"/>
                </svg>
                Edit Employee
            </a>
        </div>
    </div>

    {{-- Employee profile card --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200
                    bg-white shadow-sm">

        <div class="border-b border-slate-200 bg-gradient-to-r
                    from-slate-50 to-white p-6 sm:p-8">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center">

                <div class="flex h-20 w-20 shrink-0 items-center
                            justify-center rounded-2xl bg-blue-100
                            text-3xl font-bold text-blue-700 ring-1 ring-blue-200">
                    {{ strtoupper(mb_substr($employee->name, 0, 1)) }}
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                            {{ $employee->name }}
                        </h2>

                        @php
                            $statusClasses = match ($employee->status) {
                                'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                'inactive' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
                                default => 'bg-red-50 text-red-700 ring-red-600/20',
                            };
                        @endphp

                        <span class="inline-flex items-center rounded-full
                                     px-3 py-1 text-xs font-semibold
                                     ring-1 ring-inset {{ $statusClasses }}">
                            <span class="mr-2 h-1.5 w-1.5 rounded-full
                                {{ $employee->status === 'active'
                                    ? 'bg-emerald-500'
                                    : ($employee->status === 'pending'
                                        ? 'bg-amber-500'
                                        : 'bg-slate-400') }}">
                            </span>
                            {{ ucfirst($employee->status) }}
                        </span>
                    </div>

                    <p class="mt-2 text-sm text-slate-500">
                        Employee ID:
                        <span class="font-mono font-semibold text-slate-700">
                            {{ $employee->nik }}
                        </span>
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $employee->position ?: 'Position not assigned' }}
                        @if ($employee->department)
                            <span class="mx-1">·</span>
                            {{ $employee->department }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Information groups --}}
        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2 sm:p-8">

            {{-- Personal information --}}
            <div class="space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="font-semibold text-slate-900">
                        Personal Information
                    </h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Employee identity and contact information.
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Full Name
                    </p>
                    <p class="mt-1 break-words text-sm font-semibold text-slate-800">
                        {{ $employee->name }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Employee ID (NIK)
                    </p>
                    <p class="mt-1 font-mono text-sm font-semibold text-slate-800">
                        {{ $employee->nik }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Email Address
                    </p>
                    <p class="mt-1 break-words text-sm text-slate-800">
                        @if ($employee->email)
                            <a href="mailto:{{ $employee->email }}"
                               class="text-blue-600 hover:underline">
                                {{ $employee->email }}
                            </a>
                        @else
                            <span class="text-slate-400">Not provided</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Organizational information --}}
            <div class="space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="font-semibold text-slate-900">
                        Organizational Information
                    </h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Department assignment and system access.
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Department
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-800">
                        {{ $employee->department ?: 'Not assigned' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Position
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-800">
                        {{ $employee->position ?: 'Not assigned' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Access Role
                    </p>
                    <span class="mt-1 inline-flex rounded-lg bg-blue-50
                                 px-3 py-1.5 text-sm font-semibold text-blue-700">
                        {{ ucwords(str_replace('_', ' ', $employee->role)) }}
                    </span>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Account Status
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-800">
                        {{ ucfirst($employee->status) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Account timestamps --}}
        <div class="border-t border-slate-200 bg-slate-50/70 p-6 sm:p-8">
            <h3 class="font-semibold text-slate-900">
                Account Record
            </h3>

            <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Registered At
                    </p>
                    <p class="mt-1 text-sm text-slate-800">
                        {{ $employee->created_at?->format('d M Y, H:i') ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Last Updated
                    </p>
                    <p class="mt-1 text-sm text-slate-800">
                        {{ $employee->updated_at?->format('d M Y, H:i') ?? '—' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <p class="text-xs text-slate-400">
        Employee record ID: {{ $employee->id }}
    </p>
    {{-- Account Status Management --}}
<section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h3 class="font-semibold text-slate-900">
                Account Status Management
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Control whether this employee can access DRICS.
            </p>
        </div>

        @if ($employee->status === 'pending')
            <a
                href="{{ route('admin.user-approvals.index') }}"
                class="inline-flex items-center justify-center rounded-xl
                       border border-amber-300 bg-amber-50 px-4 py-2.5
                       text-sm font-semibold text-amber-800 hover:bg-amber-100"
            >
                Open User Approvals
            </a>
        @else
            <form
                action="{{ route('admin.employees.status.update', $employee) }}"
                method="POST"
                onsubmit="return confirm(
                    'Are you sure you want to change this employee account status?'
                )"
            >
                @csrf
                @method('PATCH')

                <input
                    type="hidden"
                    name="status"
                    value="{{ $employee->status === 'active' ? 'inactive' : 'active' }}"
                >

                @if ($employee->status === 'active')
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl
                               border border-red-200 bg-white px-4 py-2.5
                               text-sm font-semibold text-red-700
                               transition hover:bg-red-50
                               focus:outline-none focus:ring-2
                               focus:ring-red-500 focus:ring-offset-2"
                    >
                        Deactivate Account
                    </button>
                @else
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl
                               bg-emerald-600 px-4 py-2.5 text-sm font-semibold
                               text-white transition hover:bg-emerald-700
                               focus:outline-none focus:ring-2
                               focus:ring-emerald-500 focus:ring-offset-2"
                    >
                        Activate Account
                    </button>
                @endif
            </form>
        @endif
    </div>

    @if ($employee->status === 'pending')
        <p class="mt-4 rounded-xl bg-amber-50 px-4 py-3 text-sm text-amber-800">
            This account is awaiting approval. Process it through User Approvals
            instead of changing its status directly.
        </p>
    @elseif ($employee->status === 'inactive')
        <p class="mt-4 rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-600">
            This account is inactive. The employee should not be able to access
            protected DRICS pages until the account is activated.
        </p>
    @endif
</section>
</div>

@endsection
