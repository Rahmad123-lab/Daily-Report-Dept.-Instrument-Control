@extends('layouts.app')

@section('title', 'Assessment History')
@section('header', 'Assessment History')

@section('content')

<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <p class="text-sm font-medium text-blue-600">
                Competency Management
            </p>

            <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-800">
                Assessment History
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Historical records of employee competency assessments.
            </p>
        </div>

        <a href="{{ route('employee-competencies.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">

            <svg class="h-4 w-4"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 19l-7-7 7-7" />
            </svg>

            Back to Competency
        </a>

    </div>


    {{-- Summary --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Total History Records
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ number_format($assessmentHistories->total()) }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                Based on current filters
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Current Page
            </p>

            <p class="mt-2 text-3xl font-bold text-blue-600">
                {{ $assessmentHistories->count() }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                Records displayed on this page
            </p>
        </div>

    </div>


    {{-- Filters --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="mb-5">
            <h3 class="text-base font-bold text-slate-800">
                Search & Filter
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Find assessment history using employee, competency, category, or assessment date.
            </p>
        </div>

        <form method="GET"
              action="{{ route('employee-competencies.history') }}"
              class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">

            {{-- Search --}}
            <div>
                <label for="search"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Search
                </label>

                <input type="text"
                       name="search"
                       id="search"
                       value="{{ request('search') }}"
                       placeholder="Name, NIK, competency, evidence..."
                       class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            {{-- Employee Filter --}}
            @if($isAdmin)
                <div>
                    <label for="user_id"
                           class="mb-2 block text-sm font-semibold text-slate-700">
                        Employee
                    </label>

                    <select name="user_id"
                            id="user_id"
                            class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">

                        <option value="">
                            All Employees
                        </option>

                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"
                                @selected(request('user_id') == $employee->id)>

                                {{ $employee->name }}
                                ({{ $employee->nik }})

                            </option>
                        @endforeach

                    </select>
                </div>
            @endif

            {{-- Category Filter --}}
            <div>
                <label for="category"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Competency Category
                </label>

                <select name="category"
                        id="category"
                        class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">

                    <option value="">
                        All Categories
                    </option>

                    @foreach($categories as $category)
                        <option value="{{ $category }}"
                            @selected(request('category') === $category)>

                            {{ $category }}

                        </option>
                    @endforeach

                </select>
            </div>

            {{-- From Date --}}
            <div>
                <label for="from_date"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Assessment From
                </label>

                <input type="date"
                       name="from_date"
                       id="from_date"
                       value="{{ request('from_date') }}"
                       class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            {{-- To Date --}}
            <div>
                <label for="to_date"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Assessment Until
                </label>

                <input type="date"
                       name="to_date"
                       id="to_date"
                       value="{{ request('to_date') }}"
                       class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            {{-- Actions --}}
            <div class="flex items-end gap-2">

                <button type="submit"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">

                    <svg class="h-4 w-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>

                    Apply Filter
                </button>

                <a href="{{ route('employee-competencies.history') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">

                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- History Table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h3 class="font-bold text-slate-800">
                    Assessment Records
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Chronological competency assessment history.
                </p>
            </div>

            <span class="text-xs font-medium text-slate-500">
                {{ $assessmentHistories->firstItem() ?? 0 }}
                -
                {{ $assessmentHistories->lastItem() ?? 0 }}
                of {{ $assessmentHistories->total() }} records
            </span>

        </div>

        @if($assessmentHistories->isEmpty())

            <div class="px-6 py-16 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">

                    <svg class="h-7 w-7"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.7"
                              d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                </div>

                <h4 class="mt-4 text-base font-bold text-slate-800">
                    No Assessment History Found
                </h4>

                <p class="mt-1 text-sm text-slate-500">
                    There are no assessment records matching your filters.
                </p>

            </div>

        @else

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-100">

                    <thead class="bg-slate-50">

                        <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-500">

                            <th class="whitespace-nowrap px-5 py-4">
                                Assessment Date
                            </th>

                            @if($isAdmin)
                                <th class="whitespace-nowrap px-5 py-4">
                                    Employee
                                </th>
                            @endif

                            <th class="whitespace-nowrap px-5 py-4">
                                Competency
                            </th>

                            <th class="whitespace-nowrap px-5 py-4">
                                Level
                            </th>

                            <th class="whitespace-nowrap px-5 py-4">
                                Assessor
                            </th>

                            <th class="min-w-64 px-5 py-4">
                                Evidence & Notes
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">

                        @foreach($assessmentHistories as $history)

                            <tr class="align-top transition hover:bg-slate-50/70">

                                {{-- Date --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    <p class="text-sm font-semibold text-slate-700">
                                        {{ $history->assessed_at?->format('d M Y') ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        Record #{{ $history->id }}
                                    </p>

                                </td>


                                {{-- Employee --}}
                                @if($isAdmin)
                                    <td class="px-5 py-4">

                                        <p class="text-sm font-semibold text-slate-800">
                                            {{ $history->employee_name ?: 'Unknown Employee' }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            {{ $history->employee?->nik ?? 'NIK unavailable' }}
                                        </p>

                                    </td>
                                @endif


                                {{-- Competency --}}
                                <td class="px-5 py-4">

                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $history->competency_name }}
                                    </p>

                                    <p class="mt-1 text-xs font-medium text-blue-600">
                                        {{ $history->competency_code }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $history->competency_category ?: 'Uncategorized' }}
                                    </p>

                                </td>


                                {{-- Level --}}
                                <td class="px-5 py-4">

                                    <div class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-1.5">

                                        <span class="text-sm font-bold text-slate-800">
                                            {{ $history->current_level }}
                                        </span>

                                        <span class="mx-1.5 text-xs text-slate-400">
                                            /
                                        </span>

                                        <span class="text-sm font-semibold text-slate-500">
                                            {{ $history->target_level }}
                                        </span>

                                    </div>

                                    @if($history->current_level >= $history->target_level)

                                        <p class="mt-2 text-xs font-semibold text-emerald-600">
                                            Target Achieved
                                        </p>

                                    @else

                                        <p class="mt-2 text-xs font-semibold text-amber-600">
                                            Gap:
                                            {{ $history->target_level - $history->current_level }}
                                        </p>

                                    @endif

                                </td>


                                {{-- Assessor --}}
                                <td class="px-5 py-4">

                                    <p class="text-sm font-medium text-slate-700">
                                        {{ $history->assessor_name
                                            ?: $history->assessor?->name
                                            ?: 'Unknown' }}
                                    </p>

                                </td>


                                {{-- Evidence and Notes --}}
                                <td class="px-5 py-4">

                                    <div class="space-y-3">

                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                                Evidence
                                            </p>

                                            <p class="mt-1 whitespace-pre-line text-sm leading-5 text-slate-600">
                                                {{ $history->evidence ?: 'No evidence provided.' }}
                                            </p>
                                        </div>

                                        @if($history->notes)

                                            <div>
                                                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">
                                                    Notes
                                                </p>

                                                <p class="mt-1 whitespace-pre-line text-sm leading-5 text-slate-600">
                                                    {{ $history->notes }}
                                                </p>
                                            </div>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $assessmentHistories->links() }}
            </div>

        @endif

    </div>

</div>

@endsection
