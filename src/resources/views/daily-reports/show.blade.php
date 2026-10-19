@extends('layouts.app')

@section('title', 'Detail Daily Report')

@section('content')

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 px-5 py-4 rounded-xl bg-green-100 text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 px-5 py-4 rounded-xl bg-red-100 text-red-700 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-6">

        {{-- =========================================================
            HEADER
        ========================================================== --}}
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Daily Report Detail
                </h1>

                <p class="text-slate-500">
                    Detail aktivitas pekerjaan harian
                </p>
            </div>

            <a
                href="{{ route('daily-reports.index') }}"
                class="px-5 py-3 rounded-xl border bg-white hover:bg-slate-50 transition"
            >
                ← Back
            </a>

        </div>


        {{-- =========================================================
            REPORT INFORMATION
        ========================================================== --}}
        <div class="bg-white rounded-2xl border shadow-sm p-6">

            {{-- Report Header --}}
            <div class="flex items-center justify-between mb-6">

                <div>
                    <h2 class="text-xl font-bold text-slate-800">
                        {{ $dailyReport->report_number }}
                    </h2>

                    <p class="text-sm text-slate-500">
                        Daily Activity Report
                    </p>
                </div>


                {{-- =================================================
                    REPORT ACTIONS
                ================================================== --}}
                <div class="flex items-center gap-2">

                    {{-- Draft Actions --}}
                    @if($dailyReport->status === 'Draft')

                        {{-- Edit --}}
                        <a
                            href="{{ route('daily-reports.edit', $dailyReport) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition"
                        >
                            Edit
                        </a>


                        {{-- Publish --}}
                        <form
                            method="POST"
                            action="{{ route('daily-reports.publish', $dailyReport) }}"
                            onsubmit="return confirm('Publish Daily Report ini? Setelah dipublish, report tidak dapat diedit.')"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition"
                            >
                                Publish
                            </button>
                        </form>

                    @endif


                    {{-- Published State --}}
                    @if($dailyReport->status === 'Published')

                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-100 text-emerald-700 font-semibold"
                        >
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>

                            Published
                        </span>

                    @endif

                </div>

            </div>


            {{-- =================================================
                REPORT META INFORMATION
            ================================================== --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

                {{-- Date --}}
                <div>
                    <p class="text-sm text-slate-500">
                        Date
                    </p>

                    <p class="font-semibold text-slate-800">
                        {{ $dailyReport->report_date->format('d M Y') }}
                    </p>
                </div>


                {{-- Shift --}}
                <div>
                    <p class="text-sm text-slate-500">
                        Shift
                    </p>

                    <p class="font-semibold text-slate-800">
                        {{ $dailyReport->shift }}
                    </p>
                </div>


                {{-- Division --}}
                <div>
                    <p class="text-sm text-slate-500">
                        Division
                    </p>

                    <p class="font-semibold text-slate-800">
                        {{ $dailyReport->division }}
                    </p>
                </div>


                {{-- Creator --}}
                <div>
                    <p class="text-sm text-slate-500">
                        Creator
                    </p>

                    <p class="font-semibold text-slate-800">
                        {{ $dailyReport->creator->name ?? '-' }}
                    </p>
                </div>

            </div>

        </div>


        {{-- =========================================================
            WORK ACTIVITIES
        ========================================================== --}}
        <div class="bg-white rounded-2xl border shadow-sm">

            {{-- Section Header --}}
            <div class="p-6 border-b flex items-center justify-between">

                <div>
                    <h2 class="text-xl font-bold text-slate-800">
                        Work Activities
                    </h2>

                    <p class="text-sm text-slate-500">
                        List of activities performed in this daily report
                    </p>
                </div>


                {{-- Add Activity --}}
                @if($dailyReport->status === 'Draft')

                    <button
                        type="button"
                        onclick="document.getElementById('activity-form').classList.toggle('hidden')"
                        class="px-5 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition"
                    >
                        + Add Activity
                    </button>

                @endif

            </div>


            {{-- =====================================================
                ADD ACTIVITY FORM
            ====================================================== --}}
            @if($dailyReport->status === 'Draft')

                <div
                    id="activity-form"
                    class="hidden p-6 bg-slate-50 border-b"
                >

                    <form
                        method="POST"
                        action="{{ route('daily-reports.items.store', $dailyReport) }}"
                        class="space-y-5"
                    >

                        @csrf


                        {{-- Category --}}
                        <div>

                            <label class="block text-sm font-medium mb-2">
                                Category
                            </label>

                            <select
                                name="category"
                                class="w-full rounded-xl border-slate-300"
                                required
                            >

                                <option value="">
                                    Select Category
                                </option>

                                <option value="Perintah Atasan">
                                    Perintah Atasan
                                </option>

                                <option value="Pekerjaan Rutin">
                                    Pekerjaan Rutin
                                </option>

                                <option value="Buku Catat">
                                    Buku Catat
                                </option>

                                <option value="Preventive">
                                    Preventive
                                </option>

                                <option value="Corrective">
                                    Corrective
                                </option>

                                <option value="Emergency">
                                    Emergency
                                </option>

                            </select>

                        </div>


                        {{-- Title --}}
                        <div>

                            <label class="block text-sm font-medium mb-2">
                                Title
                            </label>

                            <input
                                type="text"
                                name="title"
                                value="{{ old('title') }}"
                                class="w-full rounded-xl border-slate-300"
                                placeholder="Contoh: Kalibrasi Flowmeter FT-201"
                                required
                            >

                        </div>


                        {{-- Description --}}
                        <div>

                            <label class="block text-sm font-medium mb-2">
                                Description
                            </label>

                            <textarea
                                name="description"
                                rows="4"
                                class="w-full rounded-xl border-slate-300"
                                placeholder="Deskripsi pekerjaan..."
                                required
                            >{{ old('description') }}</textarea>

                        </div>


                        {{-- SPK + Status --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            {{-- SPK --}}
                            <div>

                                <label class="block text-sm font-medium mb-2">
                                    SPK Number
                                </label>

                                <input
                                    type="text"
                                    name="spk_number"
                                    value="{{ old('spk_number') }}"
                                    class="w-full rounded-xl border-slate-300"
                                >

                            </div>


                            {{-- Status --}}
                            <div>

                                <label class="block text-sm font-medium mb-2">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    class="w-full rounded-xl border-slate-300"
                                    required
                                >

                                    <option value="Open">
                                        Open
                                    </option>

                                    <option value="Progress">
                                        Progress
                                    </option>

                                    <option value="Done">
                                        Done
                                    </option>

                                </select>

                            </div>

                        </div>


                        {{-- Workers --}}
                        <div>

                            <label class="block text-sm font-medium mb-2">
                                Workers
                            </label>

                            <select
                                name="workers[]"
                                multiple
                                class="w-full rounded-xl border-slate-300"
                            >

                                @foreach($workers as $worker)

                                    <option
                                        value="{{ $worker->id }}"
                                        @selected(
                                            in_array(
                                                $worker->id,
                                                old('workers', [])
                                            )
                                        )
                                    >
                                        {{ $worker->name }}
                                    </option>

                                @endforeach

                            </select>

                            <p class="text-xs text-slate-500 mt-1">
                                Gunakan CTRL untuk memilih beberapa worker.
                            </p>

                        </div>


                        {{-- Submit --}}
                        <div class="flex justify-end">

                            <button
                                type="submit"
                                class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition"
                            >
                                Save Activity
                            </button>

                        </div>

                    </form>

                </div>

            @endif


            {{-- =====================================================
                ACTIVITY LIST
            ====================================================== --}}
            <div class="divide-y">

                @forelse($dailyReport->items as $item)

                    <div class="p-6">

                        <div class="flex items-start justify-between">

                            {{-- Activity Information --}}
                            <div>

                                <span
                                    class="text-xs px-3 py-1 rounded-full bg-blue-100 text-blue-700"
                                >
                                    {{ $item->category }}
                                </span>


                                <h3 class="mt-3 text-lg font-bold text-slate-800">
                                    {{ $item->title }}
                                </h3>

                            </div>


                            {{-- Activity Actions --}}
                            <div class="flex items-center gap-2">

                                @if($dailyReport->status === 'Draft')

                                    {{-- Edit Activity --}}
                                    <a
                                        href="{{ route(
                                            'daily-reports.items.edit',
                                            [
                                                $dailyReport,
                                                $item
                                            ]
                                        ) }}"
                                        class="px-3 py-2 text-sm rounded-lg bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition"
                                    >
                                        Edit
                                    </a>


                                    {{-- Delete Activity --}}
                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'daily-reports.items.destroy',
                                            [
                                                $dailyReport,
                                                $item
                                            ]
                                        ) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus activity ini?')"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="px-3 py-2 text-sm rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                @endif


                                {{-- Activity Status --}}
                                <span
                                    class="px-3 py-1 rounded-lg bg-slate-100 text-sm"
                                >
                                    {{ $item->status }}
                                </span>

                            </div>

                        </div>


                        {{-- Description --}}
                        <p class="mt-3 text-slate-600">
                            {{ $item->description }}
                        </p>


                        {{-- Activity Metadata --}}
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                            {{-- SPK --}}
                            <div>

                                <span class="text-slate-500">
                                    SPK Number
                                </span>

                                <p class="font-semibold">
                                    {{ $item->spk_number ?? '-' }}
                                </p>

                            </div>


                            {{-- Workers --}}
                            <div>

                                <span class="text-slate-500">
                                    Workers
                                </span>

                                <p class="font-semibold">

                                    @forelse($item->workers as $worker)

                                        {{ $worker->name }}

                                        @if(!$loop->last)
                                            ,
                                        @endif

                                    @empty

                                        -

                                    @endforelse

                                </p>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="p-10 text-center text-slate-500">
                        Belum ada aktivitas pekerjaan.
                    </div>

                @endforelse

            </div>

        </div>

    </div>

@endsection