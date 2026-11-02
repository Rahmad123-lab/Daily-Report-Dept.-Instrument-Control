@extends('layouts.app')

@section('title', 'Competency Details')
@section('header', 'Competency Details')

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

<div class="mx-auto max-w-5xl space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center
                sm:justify-between">

        <a href="{{ route('competencies.index') }}"
           class="text-sm font-medium text-slate-500 hover:text-blue-600">
            ← Back to Competency List
        </a>

        @if(auth()->user()->role === 'admin')
            <a href="{{ route('competencies.edit', $competency) }}"
               class="rounded-xl bg-blue-600 px-5 py-3 text-center
                      text-sm font-semibold text-white hover:bg-blue-700">
                Edit Competency
            </a>
        @endif

    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200
                bg-white shadow-sm">

        <div class="border-b border-slate-200 bg-slate-50/70 p-6 sm:p-8">

            <div class="flex flex-col gap-4 sm:flex-row
                        sm:items-start sm:justify-between">

                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-blue-600">
                        {{ $competency->code }}
                    </p>

                    <h1 class="mt-2 text-2xl font-bold text-slate-800">
                        {{ $competency->name }}
                    </h1>

                    <p class="mt-2 text-sm text-slate-500">
                        {{ $competency->category }}
                    </p>
                </div>

                <span class="w-fit rounded-full px-3 py-1.5 text-xs
                             font-semibold
                             {{ $competency->is_active
                                ? 'bg-emerald-50 text-emerald-700'
                                : 'bg-slate-100 text-slate-600' }}">
                    {{ $competency->is_active ? 'Active' : 'Inactive' }}
                </span>

            </div>
        </div>

        <div class="p-6 sm:p-8">

            <h2 class="font-semibold text-slate-800">
                Description
            </h2>

            <p class="mt-3 whitespace-pre-line text-sm leading-7 text-slate-600">
                {{ $competency->description ?: 'No description has been provided.' }}
            </p>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div class="rounded-xl border border-slate-200 p-4">
                    <p class="text-sm text-slate-500">
                        Employee Assessment Records
                    </p>

                    <p class="mt-2 text-2xl font-bold text-slate-800">
                        {{ $competency->employee_competencies_count }}
                    </p>
                </div>

                <div class="rounded-xl border border-slate-200 p-4">
                    <p class="text-sm text-slate-500">
                        Competency Status
                    </p>

                    <p class="mt-2 text-lg font-bold text-slate-800">
                        {{ $competency->is_active ? 'Available' : 'Inactive' }}
                    </p>
                </div>

            </div>

        </div>

    </div>

    {{-- Admin Assessment Overview --}}
    @if(auth()->user()->role === 'admin')

        <div class="overflow-hidden rounded-2xl border border-slate-200
                    bg-white shadow-sm">

            <div class="flex flex-col gap-3 border-b border-slate-200
                        px-6 py-5 sm:flex-row sm:items-center
                        sm:justify-between">

                <div>
                    <h2 class="font-bold text-slate-800">
                        Employee Assessment Overview
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Daftar karyawan yang memiliki kompetensi ini.
                    </p>
                </div>

                <a href="{{ route('employee-competencies.index', [
                        'competency_id' => $competency->id
                    ]) }}"
                   class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                    Manage Assessments →
                </a>

            </div>

            @if($competency->employeeCompetencies->isEmpty())
                <div class="px-6 py-12 text-center">
                    <p class="font-semibold text-slate-800">
                        No assessment records
                    </p>
                    <p class="mt-1 text-sm text-slate-500">
                        Belum ada karyawan yang dinilai pada kompetensi ini.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">

                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold
                                           uppercase text-slate-500">
                                    Employee
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-semibold
                                           uppercase text-slate-500">
                                    Current
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-semibold
                                           uppercase text-slate-500">
                                    Target
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-semibold
                                           uppercase text-slate-500">
                                    Gap
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            @foreach($competency->employeeCompetencies as $record)
                                @php
                                    $gap = $record->current_level !== null
                                        ? max(0, $record->target_level - $record->current_level)
                                        : null;
                                @endphp

                                <tr>
                                    <td class="px-5 py-4">
                                        <p class="text-sm font-semibold text-slate-800">
                                            {{ $record->employee?->name ?? 'Unknown Employee' }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $record->employee?->nik ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4 text-sm text-slate-600">
                                        {{ $record->current_level !== null
                                            ? $record->current_level . ' - ' . $levelLabels[$record->current_level]
                                            : 'Not Assessed' }}
                                    </td>

                                    <td class="px-5 py-4 text-sm text-slate-600">
                                        {{ $record->target_level }} -
                                        {{ $levelLabels[$record->target_level] }}
                                    </td>

                                    <td class="px-5 py-4">
                                        @if($gap === null)
                                            <span class="text-xs font-medium text-slate-400">
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
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            @endif

        </div>

    @endif

</div>
@endsection