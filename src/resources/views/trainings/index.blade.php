@extends('layouts.app')

@section('title', 'Training Management')
@section('header', 'Training Management')

@section('content')
@php
    $statusLabels = [
        'planned' => 'Planned',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    $statusStyles = [
        'planned' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
        'in_progress' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'cancelled' => 'bg-red-50 text-red-700 ring-red-600/20',
    ];
@endphp

<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                Training Programs
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola dan pantau jadwal pelatihan karyawan.
            </p>
        </div>

        @if(auth()->user()->role === 'admin')
            <a
                href="{{ route('trainings.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl
                       bg-blue-600 px-5 py-3 text-sm font-semibold text-white
                       shadow-sm transition hover:bg-blue-700
                       focus:outline-none focus:ring-2 focus:ring-blue-500
                       focus:ring-offset-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 5v14m-7-7h14"/>
                </svg>
                Add Training
            </a>
        @endif
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Total Training
            </p>
            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ $trainings->total() }}
            </p>
            <p class="mt-1 text-xs text-slate-500">
                Berdasarkan hasil filter saat ini
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Current Page
            </p>
            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ $trainings->count() }}
            </p>
            <p class="mt-1 text-xs text-slate-500">
                Data pada halaman ini
            </p>
        </div>
    </div>

    {{-- Search and Filter --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('trainings.index') }}">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                <div class="md:col-span-7">
                    <label for="search"
                           class="mb-2 block text-sm font-medium text-slate-700">
                        Search Training
                    </label>

                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="absolute left-3 top-1/2 h-5 w-5
                                    -translate-y-1/2 text-slate-400"
                             fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="7"/>
                            <path stroke-linecap="round"
                                  d="m16 16 4 4"/>
                        </svg>

                        <input
                            id="search"
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            maxlength="150"
                            placeholder="Nama, kategori, atau penyelenggara..."
                            class="w-full rounded-xl border border-slate-300
                                   py-3 pl-10 pr-4 text-sm text-slate-800
                                   placeholder:text-slate-400
                                   focus:border-blue-500 focus:outline-none
                                   focus:ring-2 focus:ring-blue-100"
                        >
                    </div>
                </div>

                <div class="md:col-span-3">
                    <label for="status"
                           class="mb-2 block text-sm font-medium text-slate-700">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="w-full rounded-xl border border-slate-300
                               px-3 py-3 text-sm text-slate-700
                               focus:border-blue-500 focus:outline-none
                               focus:ring-2 focus:ring-blue-100"
                    >
                        <option value="">All Statuses</option>
                        @foreach($statusLabels as $value => $label)
                            <option value="{{ $value }}"
                                @selected(request('status') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2 md:col-span-2">
                    <button
                        type="submit"
                        class="flex-1 rounded-xl bg-slate-800 px-4 py-3
                               text-sm font-semibold text-white
                               transition hover:bg-slate-700"
                    >
                        Filter
                    </button>

                    <a
                        href="{{ route('trainings.index') }}"
                        title="Reset filter"
                        class="inline-flex items-center justify-center
                               rounded-xl border border-slate-300 px-3 py-3
                               text-slate-600 transition hover:bg-slate-50"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M4 4v6h6M20 20v-6h-6"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M5.5 9A7 7 0 0 1 18 6l2 4M4 14l2 4a7 7 0 0 0 12.5-3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Training Table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200
                    px-5 py-4 sm:px-6">
            <div>
                <h2 class="font-semibold text-slate-800">
                    Training List
                </h2>
                <p class="mt-1 text-xs text-slate-500">
                    Menampilkan {{ $trainings->count() }} dari
                    {{ $trainings->total() }} data
                </p>
            </div>
        </div>

        @if($trainings->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center
                            rounded-2xl bg-slate-100 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-7 w-7" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="1.6">
                        <rect x="4" y="5" width="16" height="15"
                              rx="2" stroke-linejoin="round"/>
                        <path stroke-linecap="round"
                              d="M8 3v4m8-4v4M4 10h16"/>
                    </svg>
                </div>

                <h3 class="mt-4 text-sm font-semibold text-slate-800">
                    No training found
                </h3>
                <p class="mt-1 text-sm text-slate-500">
                    Belum ada data pelatihan atau tidak ada hasil
                    yang sesuai dengan filter.
                </p>

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('trainings.create') }}"
                       class="mt-5 inline-flex rounded-xl bg-blue-600
                              px-4 py-2.5 text-sm font-semibold text-white
                              transition hover:bg-blue-700">
                        Add First Training
                    </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold
                                       uppercase tracking-wider text-slate-500 sm:px-6">
                                Training
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold
                                       uppercase tracking-wider text-slate-500">
                                Schedule
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold
                                       uppercase tracking-wider text-slate-500">
                                Participants
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold
                                       uppercase tracking-wider text-slate-500">
                                Status
                            </th>
                            <th class="px-5 py-3 text-right text-xs font-semibold
                                       uppercase tracking-wider text-slate-500 sm:px-6">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach($trainings as $training)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="px-5 py-4 sm:px-6">
                                    <a href="{{ route('trainings.show', $training) }}"
                                       class="font-semibold text-slate-800
                                              transition hover:text-blue-600">
                                        {{ $training->title }}
                                    </a>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $training->category ?: 'Uncategorized' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $training->organizer ?: 'Organizer not specified' }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                    <p>
                                        {{ $training->start_date->format('d M Y') }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-400">
                                        Until {{ $training->end_date->format('d M Y') }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                    <span class="font-semibold text-slate-800">
                                        {{ $training->participants_count }}
                                    </span>
                                    <span class="text-slate-400">
                                        / {{ $training->quota ?? '∞' }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex items-center rounded-full
                                                 px-2.5 py-1 text-xs font-semibold
                                                 ring-1 ring-inset
                                                 {{ $statusStyles[$training->status] ??
                                                    'bg-slate-100 text-slate-600 ring-slate-500/20' }}">
                                        {{ $statusLabels[$training->status] ??
                                           ucfirst($training->status) }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-right sm:px-6">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('trainings.show', $training) }}"
                                           class="text-sm font-semibold text-blue-600
                                                  hover:text-blue-800">
                                            View
                                        </a>

                                        @if(auth()->user()->role === 'admin')
                                            <a href="{{ route('trainings.edit', $training) }}"
                                               class="text-sm font-semibold text-slate-600
                                                      hover:text-slate-900">
                                                Edit
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('trainings.destroy', $training) }}"
                                                  onsubmit="return confirm('Hapus pelatihan ini beserta data peserta yang terkait? Tindakan ini tidak dapat dibatalkan.')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="text-sm font-semibold text-red-600
                                                               hover:text-red-800">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4 sm:px-6">
                {{ $trainings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection