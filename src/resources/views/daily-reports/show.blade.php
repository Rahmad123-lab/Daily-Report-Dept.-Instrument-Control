@extends('layouts.app')

@section('title', 'Detail Daily Report')

@section('content')

@if(session('success'))

    <div class="px-5 py-4 rounded-xl bg-green-100 text-green-700 border border-green-200">

        {{ session('success') }}

    </div>

@endif
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">
                Daily Report Detail
            </h1>

            <p class="text-slate-500">
                Detail aktivitas pekerjaan harian
            </p>

        </div>

        <a href="{{ route('daily-reports.index') }}"
           class="px-5 py-3 rounded-xl border bg-white hover:bg-slate-50">

            ← Back

        </a>

    </div>


    {{-- Report Information --}}
    <div class="bg-white rounded-2xl border shadow-sm p-6">

        <div class="flex items-center justify-between mb-6">

            <div>

                <h2 class="text-xl font-bold text-slate-800">
                    {{ $dailyReport->report_number }}
                </h2>

                <p class="text-sm text-slate-500">
                    Daily Activity Report
                </p>

            </div>

            <div class="flex items-center gap-2">

    {{-- Edit --}}

    @if(
        in_array(
            $dailyReport->status,
            ['Draft', 'Rejected']
        )
    )

        <a
            href="{{ route(
                'daily-reports.edit',
                $dailyReport
            ) }}"
            class="px-4 py-2 rounded-lg bg-blue-50 text-blue-600">

            Edit

        </a>

    @endif


    {{-- Submit --}}

    @if(
        in_array(
            $dailyReport->status,
            ['Draft', 'Rejected']
        )
    )

        <form
            method="POST"
            action="{{ route(
                'daily-reports.submit',
                $dailyReport
            ) }}">

            @csrf

            <button
                class="px-4 py-2 rounded-lg bg-blue-600 text-white">

                Submit

            </button>

        </form>

    @endif


    {{-- Approve --}}

    @if(
        $dailyReport->status === 'Submitted'
    )

        <form
            method="POST"
            action="{{ route(
                'daily-reports.approve',
                $dailyReport
            ) }}">

            @csrf

            <button
                class="px-4 py-2 rounded-lg bg-green-600 text-white">

                Approve

            </button>

        </form>

    @endif


    {{-- Reject --}}

    @if(
        $dailyReport->status === 'Submitted'
    )

        <form
            method="POST"
            action="{{ route(
                'daily-reports.reject',
                $dailyReport
            ) }}">

            @csrf

            <button
                class="px-4 py-2 rounded-lg bg-red-600 text-white">

                Reject

            </button>

        </form>

    @endif

</div>

        </div>


        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

            <div>

                <p class="text-sm text-slate-500">
                    Date
                </p>

                <p class="font-semibold text-slate-800">
                    {{ $dailyReport->report_date->format('d M Y') }}
                </p>

            </div>


            <div>

                <p class="text-sm text-slate-500">
                    Shift
                </p>

                <p class="font-semibold text-slate-800">
                    {{ $dailyReport->shift }}
                </p>

            </div>


            <div>

                <p class="text-sm text-slate-500">
                    Division
                </p>

                <p class="font-semibold text-slate-800">
                    {{ $dailyReport->division }}
                </p>

            </div>


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


    {{-- Work Items --}}
    <div class="bg-white rounded-2xl border shadow-sm">

        <div class="p-6 border-b flex items-center justify-between">

    <div>

        <h2 class="text-xl font-bold text-slate-800">
            Work Activities
        </h2>

        <p class="text-sm text-slate-500">
            List of activities performed in this daily report
        </p>

    </div>

    @if(in_array($dailyReport->status, ['Draft','Rejected']))

<button
    type="button"
    onclick="document.getElementById('activity-form').classList.toggle('hidden')"
    class="px-5 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700">

    + Add Activity

</button>

@endif

</div>
        <div id="activity-form"
     class="hidden p-6 bg-slate-50 border-b">

    <form
        method="POST"
        action="{{ route(
            'daily-reports.items.store',
            $dailyReport
        ) }}"
        class="space-y-5">

        @csrf


        {{-- Category --}}
        <div>

            <label class="block text-sm font-medium mb-2">
                Category
            </label>

            <select
                name="category"
                class="w-full rounded-xl border-slate-300">

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
                class="w-full rounded-xl border-slate-300"
                placeholder="Contoh: Kalibrasi Flowmeter FT-201">

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
                placeholder="Deskripsi pekerjaan..."></textarea>

        </div>


        <div class="grid grid-cols-2 gap-5">


            {{-- SPK --}}
            <div>

                <label class="block text-sm font-medium mb-2">
                    SPK Number
                </label>

                <input
                    type="text"
                    name="spk_number"
                    class="w-full rounded-xl border-slate-300">

            </div>


            {{-- Status --}}
            <div>

                <label class="block text-sm font-medium mb-2">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-xl border-slate-300">

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
                class="w-full rounded-xl border-slate-300">

                @foreach($workers as $worker)

                    <option value="{{ $worker->id }}">

                        {{ $worker->name }}

                    </option>

                @endforeach

            </select>

            <p class="text-xs text-slate-500 mt-1">

                Gunakan CTRL untuk memilih beberapa worker.

            </p>

        </div>


        <div class="flex justify-end">

            <button
                type="submit"
                class="px-6 py-3 bg-blue-600 text-white rounded-xl">

                Save Activity

            </button>

        </div>

    </form>

</div>

        <div class="divide-y">

            @forelse($dailyReport->items as $item)

                <div class="p-6">

                    <div class="flex items-start justify-between">

                        <div>

                            <span class="text-xs px-3 py-1 rounded-full bg-blue-100 text-blue-700">

                                {{ $item->category }}

                            </span>


                            <h3 class="mt-3 text-lg font-bold text-slate-800">

                                {{ $item->title }}

                            </h3>

                        </div>


                       <div class="flex items-center gap-2">

@if(in_array($dailyReport->status,['Draft','Rejected']))

<a
    href="{{ route('daily-reports.items.edit',[
        $dailyReport,
        $item
    ]) }}"
    class="px-3 py-2 text-sm rounded-lg bg-yellow-100 text-yellow-700 hover:bg-yellow-200">

    Edit

</a>

<form
    method="POST"
    action="{{ route(
        'daily-reports.items.destroy',
        [
            $dailyReport,
            $item
        ]
    ) }}"
    onsubmit="return confirm('Yakin ingin menghapus activity ini?')">

    @csrf
    @method('DELETE')

    <button
        type="submit"
        class="px-3 py-2 text-sm rounded-lg bg-red-100 text-red-600 hover:bg-red-200">

        Delete

    </button>

</form>

@endif

<span
    class="px-3 py-1 rounded-lg bg-slate-100 text-sm">

    {{ $item->status }}

</span>

</div>

                    </div>


                    <p class="mt-3 text-slate-600">

                        {{ $item->description }}

                    </p>


                    <div class="mt-4 grid grid-cols-2 gap-4 text-sm">

                        <div>

                            <span class="text-slate-500">
                                SPK Number
                            </span>

                            <p class="font-semibold">
                                {{ $item->spk_number ?? '-' }}
                            </p>

                        </div>


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