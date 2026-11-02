@extends('layouts.app')

@section('title', 'Employee Competencies')
@section('header', 'Employee Competencies')

@section('content')
@php
    $levelLabels = [
        1 => 'Beginner',
        2 => 'Basic',
        3 => 'Intermediate',
        4 => 'Advanced',
        5 => 'Expert',
    ];
@endphp

<div class="space-y-6">

    {{-- Header --}}
<div class="flex flex-col gap-4 sm:flex-row sm:items-center
            sm:justify-between">

    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            {{ auth()->user()->role === 'admin'
                ? 'Employee Competency Assessment'
                : 'My Competencies' }}
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            {{ auth()->user()->role === 'admin'
                ? 'Kelola dan evaluasi kompetensi teknis setiap karyawan.'
                : 'Lihat tingkat kompetensi dan target pengembangan kemampuanmu.' }}
        </p>
    </div>

    <a
        href="{{ route('training-recommendations.index') }}"
        class="inline-flex items-center justify-center gap-2
               rounded-xl bg-blue-600 px-4 py-3
               text-sm font-semibold text-white
               transition hover:bg-blue-700"
    >
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-5 w-5" fill="none"
             viewBox="0 0 24 24" stroke="currentColor"
             stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 6v6l4 2"/>
            <circle cx="12" cy="12" r="9"/>
        </svg>

        Training Recommendations
    </a>

</div>

    {{-- Admin Assignment Form --}}
    @if(auth()->user()->role === 'admin')

        <div class="overflow-hidden rounded-2xl border border-slate-200
                    bg-white shadow-sm">

            <div class="border-b border-slate-200 bg-slate-50/70 px-6 py-5">
                <h2 class="font-bold text-slate-800">
                    Assign Employee Competency
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Tetapkan kompetensi dan target kemampuan untuk karyawan.
                </p>
            </div>

            <form method="POST"
                  action="{{ route('employee-competencies.store') }}"
                  class="space-y-6 p-6">

                @csrf

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    <div>
                        <label for="user_id"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Employee <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="user_id"
                            id="user_id"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-3
                                   text-sm focus:border-blue-500 focus:outline-none
                                   focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="">Select Employee</option>

                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    @selected(old('user_id') == $employee->id)>
                                    {{ $employee->nik }} - {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="competency_id"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Competency <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="competency_id"
                            id="competency_id"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-3
                                   text-sm focus:border-blue-500 focus:outline-none
                                   focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="">Select Competency</option>

                            @foreach($competencies as $competency)
                                <option value="{{ $competency->id }}"
                                    @selected(old('competency_id') == $competency->id)>
                                    {{ $competency->code }} - {{ $competency->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('competency_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="current_level"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Current Level
                        </label>

                        <select
                            name="current_level"
                            id="current_level"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3
                                   text-sm focus:border-blue-500 focus:outline-none
                                   focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="">Not Assessed Yet</option>

                            @foreach($levelLabels as $level => $label)
                                <option value="{{ $level }}"
                                    @selected(old('current_level') == $level)>
                                    {{ $level }} - {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        @error('current_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="target_level"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Target Level <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="target_level"
                            id="target_level"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-3
                                   text-sm focus:border-blue-500 focus:outline-none
                                   focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="">Select Target</option>

                            @foreach($levelLabels as $level => $label)
                                <option value="{{ $level }}"
                                    @selected(old('target_level') == $level)>
                                    {{ $level }} - {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        @error('target_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="assessed_at"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Assessment Date
                        </label>

                        <input
                            type="date"
                            name="assessed_at"
                            id="assessed_at"
                            value="{{ old('assessed_at') }}"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3
                                   text-sm focus:border-blue-500 focus:outline-none
                                   focus:ring-2 focus:ring-blue-100"
                        >

                        @error('assessed_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="evidence"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Evidence
                        </label>

                        <input
                            type="text"
                            name="evidence"
                            id="evidence"
                            value="{{ old('evidence') }}"
                            maxlength="10000"
                            placeholder="Certificate, assessment document..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3
                                   text-sm focus:border-blue-500 focus:outline-none
                                   focus:ring-2 focus:ring-blue-100"
                        >

                        @error('evidence')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="notes"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Assessment Notes
                        </label>

                        <textarea
                            name="notes"
                            id="notes"
                            rows="3"
                            maxlength="10000"
                            placeholder="Additional assessment notes..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3
                                   text-sm focus:border-blue-500 focus:outline-none
                                   focus:ring-2 focus:ring-blue-100"
                        >{{ old('notes') }}</textarea>

                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="flex justify-end border-t border-slate-200 pt-5">
                    <button type="submit"
                            class="rounded-xl bg-blue-600 px-5 py-3
                                   text-sm font-semibold text-white
                                   shadow-sm hover:bg-blue-700">
                        Save Employee Competency
                    </button>
                </div>

            </form>
        </div>

    @endif

    {{-- Filters --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <form method="GET" action="{{ route('employee-competencies.index') }}">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                <div>
                    <label for="search"
                           class="mb-2 block text-sm font-semibold text-slate-700">
                        Search
                    </label>

                    <input
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        maxlength="150"
                        placeholder="Name, NIK, or competency..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3
                               text-sm focus:border-blue-500 focus:outline-none"
                    >
                </div>

                @if(auth()->user()->role === 'admin')
                    <div>
                        <label for="filter_user_id"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Employee
                        </label>

                        <select
                            id="filter_user_id"
                            name="user_id"
                            class="w-full rounded-xl border border-slate-300 px-3 py-3
                                   text-sm focus:border-blue-500 focus:outline-none"
                        >
                            <option value="">All Employees</option>

                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    @selected(request('user_id') == $employee->id)>
                                    {{ $employee->nik }} - {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label for="filter_competency_id"
                           class="mb-2 block text-sm font-semibold text-slate-700">
                        Competency
                    </label>

                    <select
                        id="filter_competency_id"
                        name="competency_id"
                        class="w-full rounded-xl border border-slate-300 px-3 py-3
                               text-sm focus:border-blue-500 focus:outline-none"
                    >
                        <option value="">All Competencies</option>

                        @foreach($competencies as $competency)
                            <option value="{{ $competency->id }}"
                                @selected(request('competency_id') == $competency->id)>
                                {{ $competency->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="mt-4 flex justify-end gap-2">
                <a href="{{ route('employee-competencies.index') }}"
                   class="rounded-xl border border-slate-300 px-4 py-2.5
                          text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    Reset
                </a>

                <button type="submit"
                        class="rounded-xl bg-slate-800 px-5 py-2.5
                               text-sm font-semibold text-white hover:bg-slate-700">
                    Filter
                </button>
            </div>

        </form>
    </div>

    {{-- Employee Competency Records --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200
                bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="font-bold text-slate-800">
                Competency Assessment Records
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                {{ $employeeCompetencies->total() }} records found.
            </p>
        </div>

        @if($employeeCompetencies->isEmpty())

            <div class="px-6 py-16 text-center">
                <h3 class="font-semibold text-slate-800">
                    No Assessment Records
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Belum ada data kompetensi yang tersedia.
                </p>
            </div>

        @else

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">
                        <tr>
                            @if(auth()->user()->role === 'admin')
                                <th class="px-5 py-3 text-left text-xs
                                           font-semibold uppercase text-slate-500">
                                    Employee
                                </th>
                            @endif

                            <th class="px-5 py-3 text-left text-xs
                                       font-semibold uppercase text-slate-500">
                                Competency
                            </th>

                            <th class="px-5 py-3 text-left text-xs
                                       font-semibold uppercase text-slate-500">
                                Current
                            </th>

                            <th class="px-5 py-3 text-left text-xs
                                       font-semibold uppercase text-slate-500">
                                Target
                            </th>

                            <th class="px-5 py-3 text-left text-xs
                                       font-semibold uppercase text-slate-500">
                                Gap
                            </th>

                            <th class="px-5 py-3 text-left text-xs
                                       font-semibold uppercase text-slate-500">
                                Assessment
                            </th>

                            @if(auth()->user()->role === 'admin')
                                <th class="px-5 py-3 text-right text-xs
                                           font-semibold uppercase text-slate-500">
                                    Actions
                                </th>
                            @endif
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @foreach($employeeCompetencies as $record)
                            @php
                                $gap = $record->current_level !== null
                                    ? max(0, $record->target_level - $record->current_level)
                                    : null;
                            @endphp

                            <tr class="align-top hover:bg-slate-50/70">

                                @if(auth()->user()->role === 'admin')
                                    <td class="px-5 py-4">
                                        <p class="text-sm font-semibold text-slate-800">
                                            {{ $record->employee?->name ?? 'Unknown Employee' }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $record->employee?->nik ?? '-' }}
                                        </p>
                                    </td>
                                @endif

                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $record->competency?->name ?? 'Deleted Competency' }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $record->competency?->code ?? '-' }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $record->competency?->category ?? '-' }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    @if($record->current_level !== null)
                                        <span class="text-sm font-semibold text-slate-800">
                                            {{ $record->current_level }}
                                        </span>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $levelLabels[$record->current_level] }}
                                        </p>
                                    @else
                                        <span class="text-xs font-medium text-slate-400">
                                            Not Assessed
                                        </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="text-sm font-semibold text-slate-800">
                                        {{ $record->target_level }}
                                    </span>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $levelLabels[$record->target_level] }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    @if($gap === null)
                                        <span class="text-xs text-slate-400">
                                            Not Assessed
                                        </span>
                                    @elseif($gap === 0)
                                        <span class="rounded-full bg-emerald-50
                                                     px-3 py-1 text-xs font-semibold
                                                     text-emerald-700">
                                            Target Met
                                        </span>
                                    @else
                                        <span class="rounded-full bg-amber-50
                                                     px-3 py-1 text-xs font-semibold
                                                     text-amber-700">
                                            Gap {{ $gap }}
                                        </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="text-xs text-slate-600">
                                        {{ $record->assessed_at?->format('d M Y') ?? 'Pending' }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $record->assessor?->name ?? 'Not assessed' }}
                                    </p>
                                </td>

                                @if(auth()->user()->role === 'admin')
                                    <td class="min-w-72 px-5 py-4 text-right">

                                        <details class="text-left">

                                            <summary class="cursor-pointer
                                                            text-sm font-semibold
                                                            text-blue-600 hover:text-blue-800">
                                                Edit Assessment
                                            </summary>

                                            <form
                                                method="POST"
                                                action="{{ route('employee-competencies.update', $record) }}"
                                                class="mt-3 space-y-3 rounded-xl
                                                       border border-slate-200 p-4"
                                            >
                                                @csrf
                                                @method('PUT')

                                                <div>
                                                    <label class="mb-1 block text-xs font-semibold text-slate-600">
                                                        Current Level
                                                    </label>

                                                    <select name="current_level"
                                                            class="w-full rounded-lg border
                                                                   border-slate-300 px-3 py-2 text-sm">
                                                        <option value="">Not Assessed</option>

                                                        @foreach($levelLabels as $level => $label)
                                                            <option value="{{ $level }}"
                                                                @selected($record->current_level === $level)>
                                                                {{ $level }} - {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-semibold text-slate-600">
                                                        Target Level
                                                    </label>

                                                    <select name="target_level" required
                                                            class="w-full rounded-lg border
                                                                   border-slate-300 px-3 py-2 text-sm">
                                                        @foreach($levelLabels as $level => $label)
                                                            <option value="{{ $level }}"
                                                                @selected($record->target_level === $level)>
                                                                {{ $level }} - {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-semibold text-slate-600">
                                                        Assessment Date
                                                    </label>

                                                    <input
                                                        type="date"
                                                        name="assessed_at"
                                                        value="{{ $record->assessed_at?->format('Y-m-d') }}"
                                                        class="w-full rounded-lg border border-slate-300
                                                               px-3 py-2 text-sm"
                                                    >
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-semibold text-slate-600">
                                                        Evidence
                                                    </label>

                                                    <textarea
                                                        name="evidence"
                                                        rows="2"
                                                        maxlength="10000"
                                                        class="w-full rounded-lg border border-slate-300
                                                               px-3 py-2 text-sm"
                                                    >{{ $record->evidence }}</textarea>
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-semibold text-slate-600">
                                                        Notes
                                                    </label>

                                                    <textarea
                                                        name="notes"
                                                        rows="2"
                                                        maxlength="10000"
                                                        class="w-full rounded-lg border border-slate-300
                                                               px-3 py-2 text-sm"
                                                    >{{ $record->notes }}</textarea>
                                                </div>

                                                <button type="submit"
                                                        class="w-full rounded-lg bg-blue-600
                                                               px-3 py-2.5 text-sm font-semibold
                                                               text-white hover:bg-blue-700">
                                                    Save Changes
                                                </button>
                                            </form>

                                        </details>

                                        <form
                                            method="POST"
                                            action="{{ route('employee-competencies.destroy', $record) }}"
                                            class="mt-3"
                                            onsubmit="return confirm('Hapus data kompetensi karyawan ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="text-xs font-semibold
                                                           text-red-600 hover:text-red-800">
                                                Delete Record
                                            </button>
                                        </form>

                                    </td>
                                @endif

                            </tr>
                        @endforeach

                    </tbody>

                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $employeeCompetencies->links() }}
            </div>

        @endif

    </div>

</div>
@endsection