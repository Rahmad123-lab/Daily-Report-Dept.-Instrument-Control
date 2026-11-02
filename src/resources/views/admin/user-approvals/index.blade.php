@extends('layouts.app')

@section('title', 'User Approvals')
@section('header', 'User Approvals')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                User Approvals
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Review employee registrations and assign access roles before activating accounts.
            </p>
        </div>

        <a
            href="{{ route('admin.employees.index') }}"
            class="inline-flex items-center justify-center rounded-xl border
                   border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold
                   text-slate-700 transition hover:bg-slate-50"
        >
            Employee Management
        </a>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div
            role="status"
            class="rounded-xl border border-emerald-200 bg-emerald-50
                   px-4 py-3 text-sm text-emerald-800"
        >
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Summary --}}
    @if ($errors->any())
        <div
            role="alert"
            class="rounded-xl border border-red-200 bg-red-50 p-4
                   text-sm text-red-800"
        >
            <p class="font-semibold">The requested action could not be completed.</p>

            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Summary --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Pending Registrations
            </p>
            <p class="mt-3 text-3xl font-bold text-slate-900">
                {{ number_format($users->total()) }}
            </p>
            <p class="mt-2 text-xs text-slate-500">
                Accounts awaiting administrator review
            </p>
        </div>

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
            <p class="text-sm font-medium text-amber-800">
                Required Action
            </p>
            <p class="mt-3 text-lg font-bold text-amber-900">
                Verify Employee Details
            </p>
            <p class="mt-2 text-xs text-amber-800">
                Check NIK, name, department, and position before approval.
            </p>
        </div>

        <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">
            <p class="text-sm font-medium text-blue-800">
                Approval Workflow
            </p>
            <p class="mt-3 text-lg font-bold text-blue-900">
                Pending → Active
            </p>
            <p class="mt-2 text-xs text-blue-800">
                A role must be assigned before account access is enabled.
            </p>
        </div>
    </div>

    {{-- Pending Registrations --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-2 border-b border-slate-200 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-slate-900">
                    Pending Employee Registrations
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Review each account and approve or reject the registration.
                </p>
            </div>

            <span class="inline-flex w-fit items-center rounded-full
                         bg-amber-50 px-3 py-1 text-xs font-semibold
                         text-amber-800 ring-1 ring-inset ring-amber-600/20">
                {{ number_format($users->total()) }} pending
            </span>
        </div>

        @forelse ($users as $user)
            <article class="border-b border-slate-100 p-5 last:border-b-0 sm:p-6">

                {{-- Employee Information --}}
                <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">

                    <div class="flex min-w-0 flex-1 items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center
                                    justify-center rounded-xl bg-blue-50
                                    text-lg font-bold text-blue-700">
                            {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-base font-semibold text-slate-900">
                                    {{ $user->name }}
                                </h3>

                                <span class="inline-flex items-center rounded-full
                                             bg-amber-50 px-2.5 py-1 text-xs
                                             font-semibold text-amber-800">
                                    Pending
                                </span>
                            </div>

                            <p class="mt-1 font-mono text-sm text-slate-600">
                                NIK: {{ $user->nik }}
                            </p>

                            <p class="mt-1 break-words text-sm text-slate-500">
                                {{ $user->email ?: 'No email provided' }}
                            </p>

                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                        Department
                                    </p>
                                    <p class="mt-1 text-sm font-medium text-slate-800">
                                        {{ $user->department ?: 'Not assigned' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                        Position
                                    </p>
                                    <p class="mt-1 text-sm font-medium text-slate-800">
                                        {{ $user->position ?: 'Not assigned' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                        Registered
                                    </p>
                                    <p class="mt-1 text-sm font-medium text-slate-800">
                                        {{ $user->created_at?->format('d M Y, H:i') ?? '—' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Approval Actions --}}
                    <div class="w-full rounded-xl border border-slate-200 bg-slate-50 p-4 xl:max-w-md">
                        <form
                            method="POST"
                            action="{{ route('admin.user-approvals.approve', $user) }}"
                            onsubmit="return confirm('Approve this employee and activate the account?')"
                        >
                            @csrf

                            <label
                                for="role-{{ $user->id }}"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Role after approval
                            </label>

                            <select
                                id="role-{{ $user->id }}"
                                name="role"
                                required
                                class="w-full rounded-xl border border-slate-300 bg-white
                                       px-3 py-2.5 text-sm text-slate-800
                                       focus:border-blue-500 focus:outline-none
                                       focus:ring-2 focus:ring-blue-100"
                            >
                                <option value="karyawan">Karyawan</option>
                                <option value="teknisi">Teknisi</option>
                                <option value="koordinator">Koordinator</option>
                                <option value="leader">Leader</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="kepala_departemen">Kepala Departemen</option>
                                <option value="kepala_divisi">Kepala Divisi</option>
                                <option value="k3">K3</option>
                                <option value="admin">Admin</option>
                            </select>

                            <button
                                type="submit"
                                class="mt-3 inline-flex w-full items-center
                                       justify-center rounded-xl bg-emerald-600
                                       px-4 py-2.5 text-sm font-semibold text-white
                                       transition hover:bg-emerald-700
                                       focus:outline-none focus:ring-2
                                       focus:ring-emerald-500 focus:ring-offset-2"
                            >
                                Approve and Activate
                            </button>
                        </form>

                        <form
                            method="POST"
                            action="{{ route('admin.user-approvals.reject', $user) }}"
                            class="mt-3"
                            onsubmit="return confirm('Reject this registration? The account will be marked inactive.')"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center
                                       rounded-xl border border-red-200 bg-white
                                       px-4 py-2.5 text-sm font-semibold text-red-700
                                       transition hover:bg-red-50
                                       focus:outline-none focus:ring-2
                                       focus:ring-red-500 focus:ring-offset-2"
                            >
                                Reject Registration
                            </button>
                        </form>
                    </div>
                </div>
            </article>

        @empty
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center
                            rounded-2xl bg-emerald-50 text-emerald-600">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="28"
                        height="28"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="m5 12 4 4L19 6"/>
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
                </div>

                <h3 class="mt-4 font-semibold text-slate-900">
                    No Pending Registrations
                </h3>

                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                    There are currently no employee accounts waiting for approval.
                    New registrations will appear here.
                </p>

                <a
                    href="{{ route('admin.employees.index') }}"
                    class="mt-5 inline-flex items-center justify-center
                           rounded-xl bg-blue-600 px-4 py-2.5 text-sm
                           font-semibold text-white transition hover:bg-blue-700"
                >
                    View Employee Directory
                </a>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if ($users->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $users->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
