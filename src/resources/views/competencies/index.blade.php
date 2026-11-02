@extends('layouts.app')

@section('title', 'Competency Management')
@section('header', 'Competency Management')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Competency Master
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Master data kompetensi teknis dan profesional karyawan.
            </p>
        </div>

        @if(auth()->user()->role === 'admin')
    <div class="flex flex-wrap items-center gap-2">

        <a
            href="{{ route('competencies.dashboard') }}"
            class="inline-flex items-center justify-center gap-2
                   rounded-xl border border-slate-300 bg-white
                   px-4 py-3 text-sm font-semibold text-slate-700
                   transition hover:bg-slate-50"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor"
                 stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 3v18h18M18 17V9M13 17V5M8 17v-3"/>
            </svg>
            Gap Analysis
        </a>

        <a
            href="{{ route('competencies.create') }}"
            class="inline-flex items-center justify-center gap-2
                   rounded-xl bg-blue-600 px-5 py-3
                   text-sm font-semibold text-white
                   shadow-sm transition hover:bg-blue-700"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor"
                 stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 5v14m-7-7h14"/>
            </svg>
            Add Competency
        </a>

    </div>
@endif
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Total Competencies
            </p>
            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ $competencies->total() }}
            </p>
            <p class="mt-1 text-xs text-slate-500">
                Records matching current filters
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Current Page
            </p>
            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ $competencies->count() }}
            </p>
            <p class="mt-1 text-xs text-slate-500">
                Competencies displayed
            </p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('competencies.index') }}">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-12">

                <div class="md:col-span-7">
                    <label for="search"
                           class="mb-2 block text-sm font-semibold text-slate-700">
                        Search Competency
                    </label>

                    <input
                        id="search"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        maxlength="150"
                        placeholder="Search code, name, or category..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3
                               text-sm focus:border-blue-500 focus:outline-none
                               focus:ring-2 focus:ring-blue-100"
                    >
                </div>

                <div class="md:col-span-3">
                    <label for="category"
                           class="mb-2 block text-sm font-semibold text-slate-700">
                        Category
                    </label>

                    <input
                        id="category"
                        type="text"
                        name="category"
                        value="{{ request('category') }}"
                        maxlength="100"
                        placeholder="Instrumentation..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3
                               text-sm focus:border-blue-500 focus:outline-none
                               focus:ring-2 focus:ring-blue-100"
                    >
                </div>

                @if(auth()->user()->role === 'admin')
                    <div class="md:col-span-2">
                        <label for="status"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Status
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="w-full rounded-xl border border-slate-300 px-3 py-3
                                   text-sm focus:border-blue-500 focus:outline-none"
                        >
                            <option value="">All</option>
                            <option value="active"
                                @selected(request('status') === 'active')>
                                Active
                            </option>
                            <option value="inactive"
                                @selected(request('status') === 'inactive')>
                                Inactive
                            </option>
                        </select>
                    </div>
                @endif
            </div>

            <div class="mt-4 flex justify-end gap-2">
                <a href="{{ route('competencies.index') }}"
                   class="rounded-xl border border-slate-300 px-4 py-2.5
                          text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    Reset
                </a>

                <button type="submit"
                        class="rounded-xl bg-slate-800 px-5 py-2.5
                               text-sm font-semibold text-white hover:bg-slate-700">
                    Apply Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Competency Cards --}}
    @if($competencies->isEmpty())
        <div class="rounded-2xl border border-slate-200 bg-white px-6 py-16
                    text-center shadow-sm">
            <div class="mx-auto flex h-14 w-14 items-center justify-center
                        rounded-2xl bg-slate-100 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-7 w-7" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor"
                     stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                </svg>
            </div>

            <h3 class="mt-4 font-semibold text-slate-800">
                No Competencies Found
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Belum terdapat kompetensi yang sesuai dengan pencarian.
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

            @foreach($competencies as $competency)
                <div class="rounded-2xl border border-slate-200 bg-white
                            p-5 shadow-sm transition hover:shadow-md">

                    <div class="flex items-start justify-between gap-3">

                        <div class="min-w-0">
                            <p class="text-xs font-semibold tracking-wide text-blue-600">
                                {{ $competency->code }}
                            </p>

                            <h3 class="mt-2 text-lg font-bold text-slate-800">
                                {{ $competency->name }}
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ $competency->category }}
                            </p>
                        </div>

                        <span class="inline-flex shrink-0 rounded-full px-3 py-1
                                     text-xs font-semibold
                                     {{ $competency->is_active
                                        ? 'bg-emerald-50 text-emerald-700'
                                        : 'bg-slate-100 text-slate-600' }}">
                            {{ $competency->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <p class="mt-4 line-clamp-2 text-sm leading-6 text-slate-500">
                        {{ $competency->description ?: 'No description provided.' }}
                    </p>

                    <div class="mt-5 flex items-center justify-between
                                border-t border-slate-100 pt-4">

                        <span class="text-xs text-slate-500">
                            {{ $competency->employee_competencies_count }}
                            employee records
                        </span>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('competencies.show', $competency) }}"
                               class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                                Details
                            </a>

                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('competencies.edit', $competency) }}"
                                   class="text-sm font-semibold text-slate-600 hover:text-slate-900">
                                    Edit
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach

        </div>

        <div class="pt-2">
            {{ $competencies->links() }}
        </div>
    @endif

</div>
@endsection