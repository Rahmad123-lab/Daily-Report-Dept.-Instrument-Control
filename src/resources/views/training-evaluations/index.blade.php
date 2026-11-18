@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- Report Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center
                sm:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Training Evaluation Report
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Laporan hasil evaluasi kompetensi setelah training.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">

            {{-- Export PDF --}}
            <a href="{{ route('trainings.evaluations.export.pdf', [
                'training' => $training->id,
                'search' => $search,
                'category' => $category,
            ]) }}"
               class="inline-flex items-center justify-center gap-2
                      rounded-lg bg-red-600 px-4 py-2.5
                      text-sm font-semibold text-white
                      shadow-sm transition hover:bg-red-700
                      focus:outline-none focus:ring-2
                      focus:ring-red-500 focus:ring-offset-2">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 10v6m0 0l-3-3m3 3l3-3M4 19h16"/>
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M4 5h16v11H4z"/>
                </svg>

                Export PDF
            </a>

            {{-- Back --}}
            <a href="{{ route('trainings.show', $training) }}"
               class="inline-flex items-center justify-center px-4 py-2.5
                      text-sm font-medium text-gray-700 bg-white
                      border border-gray-300 rounded-lg
                      hover:bg-gray-50 transition">
                Kembali ke Training
            </a>

        </div>
    </div>


    {{-- Training Information --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6">

        <p class="text-sm font-medium text-blue-600 uppercase tracking-wide">
            Training Program
        </p>

        <h2 class="mt-2 text-xl font-bold text-gray-800">
            {{ $training->title }}
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5">

            <div>
                <p class="text-sm text-gray-500">Department</p>
                <p class="mt-1 font-medium text-gray-800">
                    {{ $training->organizer }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Instructor</p>
                <p class="mt-1 font-medium text-gray-800">
                    {{ $training->instructor }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Start Date</p>
                <p class="mt-1 font-medium text-gray-800">
                    {{ \Carbon\Carbon::parse($training->start_date)->format('d M Y') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">End Date</p>
                <p class="mt-1 font-medium text-gray-800">
                    {{ \Carbon\Carbon::parse($training->end_date)->format('d M Y') }}
                </p>
            </div>

        </div>
    </div>


    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Evaluations --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">

            <p class="text-sm text-gray-500">
                Total Evaluations
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-800">
                {{ $totalEvaluations }}
            </p>

            <p class="mt-1 text-xs text-gray-500">
                Total kompetensi yang dievaluasi
            </p>

        </div>


        {{-- Employees Evaluated --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">

            <p class="text-sm text-gray-500">
                Employees Evaluated
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-800">
                {{ $totalEmployees }}
            </p>

            <p class="mt-1 text-xs text-gray-500">
                Jumlah karyawan yang dievaluasi
            </p>

        </div>


        {{-- Average Post Level --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">

            <p class="text-sm text-gray-500">
                Average Post Level
            </p>

            <p class="mt-2 text-3xl font-bold text-blue-600">
                {{ $averagePostLevel !== null
                    ? number_format($averagePostLevel, 1)
                    : '-' }}
            </p>

            <p class="mt-1 text-xs text-gray-500">
                Rata-rata level setelah training
            </p>

        </div>


        {{-- Target Achievement --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">

            <p class="text-sm text-gray-500">
                Target Achievement
            </p>

            <p class="mt-2 text-3xl font-bold text-green-600">
                {{ $targetAchievementRate }}%
            </p>

            <p class="mt-1 text-xs text-gray-500">
                {{ $targetAchieved }} dari {{ $totalEvaluations }} evaluasi
                mencapai target
            </p>

        </div>

    </div>


    {{-- Achievement Breakdown --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6">

        <h2 class="text-lg font-semibold text-gray-800">
            Target Achievement Summary
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5">

            {{-- Achieved --}}
            <div class="p-4 rounded-lg bg-green-50 border border-green-100">

                <p class="text-sm text-green-700">
                    Target Achieved
                </p>

                <p class="mt-1 text-2xl font-bold text-green-700">
                    {{ $targetAchieved }}
                </p>

            </div>


            {{-- Not Achieved --}}
            <div class="p-4 rounded-lg bg-amber-50 border border-amber-100">

                <p class="text-sm text-amber-700">
                    Target Not Achieved
                </p>

                <p class="mt-1 text-2xl font-bold text-amber-700">
                    {{ $targetNotAchieved }}
                </p>

            </div>

        </div>
    </div>


    {{-- Evaluation Details --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6">

        {{-- Search and Category Filter --}}
        <div class="flex flex-col sm:flex-row sm:items-center
                    sm:justify-between gap-4 mb-5">

            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    Evaluation Details
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Daftar hasil evaluasi setiap karyawan.
                </p>
            </div>


            {{-- Filter Form --}}
            <form
                method="GET"
                action="{{ route('trainings.evaluations.index', $training) }}"
                class="flex flex-col sm:flex-row gap-2"
            >

                {{-- Search --}}
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari karyawan atau kompetensi..."
                    class="w-full sm:w-64 rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >


                {{-- Category --}}
                <select
                    name="category"
                    class="w-full sm:w-48 rounded-lg border-gray-300
                           focus:border-blue-500 focus:ring-blue-500"
                >

                    <option value="">
                        Semua Kategori
                    </option>

                    @foreach ($categories as $item)
                        <option
                            value="{{ $item }}"
                            @selected($category === $item)
                        >
                            {{ $item }}
                        </option>
                    @endforeach

                </select>


                {{-- Filter Button --}}
                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white
                           rounded-lg hover:bg-blue-700
                           text-sm font-medium transition"
                >
                    Filter
                </button>


                {{-- Reset --}}
                @if ($search !== '' || $category !== '')

                    <a
                        href="{{ route('trainings.evaluations.index', $training) }}"
                        class="px-4 py-2 border border-gray-300
                               rounded-lg text-gray-700 text-sm
                               text-center hover:bg-gray-50 transition"
                    >
                        Reset
                    </a>

                @endif

            </form>

        </div>


        {{-- Evaluation Table --}}
        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-4 py-3 text-left text-xs
                                   font-semibold text-gray-500 uppercase">
                            Employee
                        </th>

                        <th class="px-4 py-3 text-left text-xs
                                   font-semibold text-gray-500 uppercase">
                            Competency
                        </th>

                        <th class="px-4 py-3 text-center text-xs
                                   font-semibold text-gray-500 uppercase">
                            Pre Level
                        </th>

                        <th class="px-4 py-3 text-center text-xs
                                   font-semibold text-gray-500 uppercase">
                            Post Level
                        </th>

                        <th class="px-4 py-3 text-center text-xs
                                   font-semibold text-gray-500 uppercase">
                            Target
                        </th>

                        <th class="px-4 py-3 text-center text-xs
                                   font-semibold text-gray-500 uppercase">
                            Improvement
                        </th>

                        <th class="px-4 py-3 text-center text-xs
                                   font-semibold text-gray-500 uppercase">
                            Result
                        </th>

                        <th class="px-4 py-3 text-left text-xs
                                   font-semibold text-gray-500 uppercase">
                            Assessment Date
                        </th>

                    </tr>

                </thead>


                <tbody class="bg-white divide-y divide-gray-100">

                    @forelse ($evaluations as $evaluation)

                        @php

                            $improvement = $evaluation->pre_training_level !== null
                                ? $evaluation->post_training_level
                                    - $evaluation->pre_training_level
                                : null;

                            $targetReached = $evaluation->post_training_level
                                >= $evaluation->target_level;

                        @endphp


                        <tr class="hover:bg-gray-50">

                            {{-- Employee --}}
                            <td class="px-4 py-4">

                                <p class="font-medium text-gray-800 text-sm">
                                    {{ $evaluation->employee_name }}
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    Employee ID: {{ $evaluation->user_id }}
                                </p>

                            </td>


                            {{-- Competency --}}
                            <td class="px-4 py-4">

                                <p class="text-sm font-medium text-gray-800">
                                    {{ $evaluation->competency_name }}
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $evaluation->competency_code }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    {{ $evaluation->competency_category ?? '-' }}
                                </p>

                            </td>


                            {{-- Pre Training Level --}}
                            <td class="px-4 py-4 text-center">

                                <span class="text-sm font-medium text-gray-700">
                                    {{ $evaluation->pre_training_level !== null
                                        ? 'Level ' . $evaluation->pre_training_level
                                        : '-' }}
                                </span>

                            </td>


                            {{-- Post Training Level --}}
                            <td class="px-4 py-4 text-center">

                                <span class="text-sm font-bold text-blue-700">
                                    Level {{ $evaluation->post_training_level }}
                                </span>

                            </td>


                            {{-- Target Level --}}
                            <td class="px-4 py-4 text-center">

                                <span class="text-sm text-gray-700">
                                    Level {{ $evaluation->target_level }}
                                </span>

                            </td>


                            {{-- Improvement --}}
                            <td class="px-4 py-4 text-center">

                                @if ($improvement === null)

                                    <span class="text-xs text-gray-500">
                                        No baseline
                                    </span>

                                @elseif ($improvement > 0)

                                    <span class="text-sm font-semibold text-green-600">
                                        +{{ $improvement }}
                                    </span>

                                @elseif ($improvement < 0)

                                    <span class="text-sm font-semibold text-red-600">
                                        {{ $improvement }}
                                    </span>

                                @else

                                    <span class="text-sm text-gray-600">
                                        0
                                    </span>

                                @endif

                            </td>


                            {{-- Target Result --}}
                            <td class="px-4 py-4 text-center">

                                @if ($targetReached)

                                    <span class="inline-flex px-2.5 py-1
                                                 text-xs font-medium rounded-full
                                                 bg-green-100 text-green-700">
                                        Achieved
                                    </span>

                                @else

                                    <span class="inline-flex px-2.5 py-1
                                                 text-xs font-medium rounded-full
                                                 bg-amber-100 text-amber-700">
                                        Not Achieved
                                    </span>

                                @endif

                            </td>


                            {{-- Assessment Date --}}
                            <td class="px-4 py-4 text-sm text-gray-600">

                                {{ \Carbon\Carbon::parse($evaluation->assessed_at)->format('d M Y') }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="px-4 py-10 text-center">

                                <p class="font-medium text-gray-700">
                                    Tidak ada hasil evaluasi.
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    Belum terdapat data evaluasi untuk training ini
                                    atau pencarian tidak menemukan hasil.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        <div class="mt-5">
            {{ $evaluations->links() }}
        </div>

    </div>

</div>

@endsection
