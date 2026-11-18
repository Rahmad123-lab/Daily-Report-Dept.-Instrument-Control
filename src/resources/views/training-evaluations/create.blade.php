@extends('layouts.app')

@section('content')
@php
    $trainingEndDate = \Carbon\Carbon::parse($training->end_date)
        ->toDateString();

    $trainingTitle = $training->title
        ?? $training->training_name
        ?? 'Training';

    $employeeName = $participant->user->name
        ?? 'Employee';
@endphp

<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center
                sm:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Evaluasi Kompetensi Training
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Catat hasil evaluasi kompetensi peserta setelah mengikuti training.
            </p>
        </div>

        <a href="{{ route('trainings.show', $training) }}"
           class="inline-flex items-center justify-center px-4 py-2
                  text-sm font-medium text-gray-700 bg-white
                  border border-gray-300 rounded-lg
                  hover:bg-gray-50 transition">
            Kembali
        </a>
    </div>

    {{-- Informasi Training --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Informasi Training
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div>
                <p class="text-sm text-gray-500">Nama Training</p>
                <p class="mt-1 font-medium text-gray-800">
                    {{ $trainingTitle }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Nama Peserta</p>
                <p class="mt-1 font-medium text-gray-800">
                    {{ $employeeName }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tanggal Mulai</p>
                <p class="mt-1 font-medium text-gray-800">
                    {{ \Carbon\Carbon::parse($training->start_date)->format('d M Y') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tanggal Selesai</p>
                <p class="mt-1 font-medium text-gray-800">
                    {{ \Carbon\Carbon::parse($training->end_date)->format('d M Y') }}
                </p>
            </div>

        </div>
    </div>

    {{-- Error Validation --}}
    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="font-semibold text-red-700">
                Terdapat kesalahan pada input:
            </p>

            <ul class="mt-2 list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Success / Error Message --}}
    @if (session('success'))
        <div class="p-4 bg-green-50 border border-green-200
                    text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 bg-red-50 border border-red-200
                    text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Form Evaluasi --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6">

        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800">
                Form Evaluasi Kompetensi
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Pilih kompetensi dan masukkan hasil penilaian setelah training.
            </p>
        </div>

        @if ($competencies->isEmpty())

            <div class="p-5 text-center bg-gray-50 border
                        border-gray-200 rounded-lg">

                <p class="font-medium text-gray-700">
                    Tidak ada kompetensi yang dapat dievaluasi.
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Kompetensi mungkin sudah dievaluasi atau belum
                    ditetapkan pada peserta.
                </p>

            </div>

        @else

            <form
                action="{{ route('trainings.participants.evaluations.store', [
                    'training' => $training->id,
                    'participant' => $participant->id
                ]) }}"
                method="POST"
                class="space-y-6"
            >
                @csrf

                {{-- Kompetensi --}}
                <div>
                    <label for="competency_id"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Kompetensi
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                            name="competency_id"
                            id="competency_id"
                            required
                            class="w-full rounded-lg border-gray-300
                                focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">Pilih Kompetensi</option>

                        @foreach ($competencies as $competency)
                            <option
                                value="{{ $competency->id }}"
                                data-baseline="{{ $competency->baseline_level !== null
                                    ? 'Level ' . $competency->baseline_level
                                    : 'Belum ada riwayat' }}"
                                data-target="{{ $competency->evaluation_target_level !== null
                                    ? 'Level ' . $competency->evaluation_target_level
                                    : 'Belum ditentukan' }}"
                                @selected(old('competency_id') == $competency->id)
                            >
                                {{ $competency->code }} - {{ $competency->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('competency_id')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Baseline dan Target --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <p class="text-sm text-gray-500">
                    Pre-Training Level
                </p>

                <p id="baselineLevel"
                class="mt-2 text-xl font-bold text-gray-800">
                    Pilih kompetensi
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Level kompetensi sebelum training dimulai.
                </p>
            </div>

            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-600">
                    Target Level
                </p>

                <p id="targetLevel"
                class="mt-2 text-xl font-bold text-blue-800">
                    Pilih kompetensi
                </p>

                <p class="mt-1 text-xs text-blue-600">
                    Target level kompetensi peserta.
                </p>
            </div>

        </div>

                {{-- Post Training Level --}}
                <div>
                    <label for="post_training_level"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Post-Training Level
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="post_training_level"
                        id="post_training_level"
                        required
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">Pilih Level</option>

                        @for ($level = 1; $level <= 5; $level++)
                            <option
                                value="{{ $level }}"
                                @selected(old('post_training_level') == $level)
                            >
                                Level {{ $level }}
                            </option>
                        @endfor
                    </select>

                    <p class="mt-1 text-xs text-gray-500">
                        Masukkan level kompetensi peserta setelah training,
                        dengan rentang 1 sampai 5.
                    </p>

                    @error('post_training_level')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Assessment Date --}}
                <div>
                    <label for="assessed_at"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Evaluasi
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        name="assessed_at"
                        id="assessed_at"
                        value="{{ old('assessed_at', now()->toDateString()) }}"
                        min="{{ $trainingEndDate }}"
                        max="{{ now()->toDateString() }}"
                        required
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >

                    @error('assessed_at')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Evidence --}}
                <div>
                    <label for="evidence"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Evidence / Bukti Evaluasi
                    </label>

                    <textarea
                        name="evidence"
                        id="evidence"
                        rows="4"
                        maxlength="10000"
                        placeholder="Masukkan bukti hasil evaluasi, hasil praktik, atau dokumentasi..."
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('evidence') }}</textarea>

                    @error('evidence')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Notes --}}
                <div>
                    <label for="notes"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Evaluasi
                    </label>

                    <textarea
                        name="notes"
                        id="notes"
                        rows="4"
                        maxlength="10000"
                        placeholder="Masukkan catatan tambahan dari evaluator..."
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('notes') }}</textarea>

                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Action --}}
                <div class="flex flex-col-reverse sm:flex-row
                            sm:justify-end gap-3 pt-4 border-t">

                    <a href="{{ route('trainings.show', $training) }}"
                       class="inline-flex justify-center items-center
                              px-5 py-2.5 text-sm font-medium
                              text-gray-700 bg-white border
                              border-gray-300 rounded-lg
                              hover:bg-gray-50">
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="inline-flex justify-center items-center
                               px-5 py-2.5 text-sm font-medium
                               text-white bg-blue-600 rounded-lg
                               hover:bg-blue-700 transition"
                    >
                        Simpan Evaluasi
                    </button>

                </div>

            </form>

        @endif

    </div>

</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const competencySelect = document.getElementById('competency_id');
    const baselineLevel = document.getElementById('baselineLevel');
    const targetLevel = document.getElementById('targetLevel');

    if (!competencySelect || !baselineLevel || !targetLevel) {
        return;
    }

    function updateCompetencyLevels() {
        const selectedOption =
            competencySelect.options[competencySelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {
            baselineLevel.textContent = 'Pilih kompetensi';
            targetLevel.textContent = 'Pilih kompetensi';
            return;
        }

        baselineLevel.textContent =
            selectedOption.dataset.baseline || 'Belum ada riwayat';

        targetLevel.textContent =
            selectedOption.dataset.target || 'Belum ditentukan';
    }

    competencySelect.addEventListener(
        'change',
        updateCompetencyLevels
    );

    updateCompetencyLevels();
});
</script>
@endsection