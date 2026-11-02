@extends('layouts.app')

@section('title', 'Training Details')
@section('header', 'Training Details')

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

<div class="mx-auto max-w-5xl space-y-6">

    {{-- Navigation and Actions --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <a href="{{ route('trainings.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium
                  text-slate-500 transition hover:text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-4 w-4" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor"
                 stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Training List
        </a>

        @if(auth()->user()->role === 'admin')
            <a href="{{ route('trainings.edit', $training) }}"
               class="inline-flex items-center justify-center gap-2
                      rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold
                      text-white shadow-sm transition hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16.862 4.487l2.651 2.651M4 20l4.5-1
                             11-11a1.875 1.875 0 0 0-2.65-2.65l-11 11L4 20z"/>
                </svg>
                Edit Training
            </a>
        @endif
    </div>

    {{-- Training Overview --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 bg-slate-50/70 p-6 sm:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-600">
                        Training Program
                    </p>

                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-800">
                        {{ $training->title }}
                    </h1>

                    <p class="mt-2 text-sm text-slate-500">
                        {{ $training->category ?: 'Uncategorized' }}
                        <span class="mx-1">·</span>
                        {{ $training->organizer ?: 'Organizer not specified' }}
                    </p>
                </div>

                <span class="inline-flex w-fit items-center rounded-full
                             px-3 py-1.5 text-xs font-semibold ring-1 ring-inset
                             {{ $statusStyles[$training->status] ??
                                'bg-slate-100 text-slate-600 ring-slate-500/20' }}">
                    {{ $statusLabels[$training->status] ??
                       ucfirst($training->status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2 sm:p-8">

            {{-- Start Date --}}
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center
                            rounded-xl bg-blue-50 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="1.8">
                        <rect x="3" y="5" width="18" height="16" rx="2"/>
                        <path stroke-linecap="round"
                              d="M16 3v4M8 3v4M3 11h18"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Start Date</p>
                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $training->start_date->format('d F Y') }}
                    </p>
                </div>
            </div>

            {{-- End Date --}}
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center
                            rounded-xl bg-indigo-50 text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="1.8">
                        <rect x="3" y="5" width="18" height="16" rx="2"/>
                        <path stroke-linecap="round"
                              d="M16 3v4M8 3v4M3 11h18"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">End Date</p>
                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $training->end_date->format('d F Y') }}
                    </p>
                </div>
            </div>

            {{-- Instructor --}}
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center
                            rounded-xl bg-emerald-50 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="1.8">
                        <circle cx="12" cy="8" r="4"/>
                        <path stroke-linecap="round"
                              d="M5 21a7 7 0 0 1 14 0"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Instructor</p>
                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $training->instructor ?: 'Not specified' }}
                    </p>
                </div>
            </div>

            {{-- Location --}}
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center
                            rounded-xl bg-amber-50 text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/>
                        <circle cx="12" cy="10" r="2.5"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Location</p>
                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $training->location ?: 'Not specified' }}
                    </p>
                </div>
            </div>

            {{-- Quota --}}
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center
                            rounded-xl bg-purple-50 text-purple-600">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/>
                        <circle cx="9.5" cy="7" r="4"/>
                        <path stroke-linecap="round"
                              d="M20 8v6m-3-3h6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Participant Quota</p>
                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $training->quota ? number_format($training->quota) : 'Unlimited / Not specified' }}
                    </p>
                </div>
            </div>

            {{-- Participants --}}
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center
                            rounded-xl bg-slate-100 text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="1.8">
                        <circle cx="9" cy="8" r="3"/>
                        <path stroke-linecap="round"
                              d="M3 20v-1a6 6 0 0 1 12 0v1M16 5a3 3 0 0 1 0 6m2 3a5 5 0 0 1 3 5v1"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Registered Records</p>
                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $training->participants_count }} participants
                    </p>
                </div>
            </div>

        </div>

        {{-- Description --}}
        <div class="border-t border-slate-200 p-6 sm:p-8">
            <h2 class="font-semibold text-slate-800">
                Description
            </h2>

            <div class="mt-3 whitespace-pre-line text-sm leading-7 text-slate-600">{{ $training->description ?: 'No description has been provided.' }}</div>
        </div>

        {{-- Metadata --}}
        <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
            <p class="text-xs text-slate-500">
                Created {{ $training->created_at->format('d M Y, H:i') }}
                <span class="mx-1">·</span>
                Last updated {{ $training->updated_at->format('d M Y, H:i') }}
            </p>
        </div>
    </div>
    
{{-- =========================================================
     RELATED COMPETENCIES
========================================================== --}}

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="text-lg font-bold text-slate-800">
            Related Competencies
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Kompetensi yang menjadi fokus pengembangan dalam pelatihan ini.
        </p>
    </div>

    <div class="p-6">
        @if($training->competencies->isEmpty())
            <p class="text-sm text-slate-500">
                Belum ada kompetensi yang dihubungkan dengan pelatihan ini.
            </p>
        @else
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                @foreach($training->competencies as $competency)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold text-blue-600">
                                    {{ $competency->code }}
                                </p>

                                <h3 class="mt-2 text-sm font-semibold text-slate-800">
                                    {{ $competency->name }}
                                </h3>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $competency->category }}
                                </p>
                            </div>

                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold
                                {{ $competency->is_active
                                    ? 'bg-emerald-50 text-emerald-700'
                                    : 'bg-slate-100 text-slate-600' }}">
                                {{ $competency->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        @if($competency->description)
                            <p class="mt-3 text-xs leading-5 text-slate-500">
                                {{ $competency->description }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

    {{-- =========================================================
     PARTICIPANT REGISTRATION
========================================================== --}}

@php
    $myRegistration = $training->participants
        ->firstWhere('user_id', auth()->id());

    $registrationLabels = [
        'registered' => 'Pending Approval',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'cancelled' => 'Cancelled',
    ];

    $registrationStyles = [
        'registered' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'approved' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'rejected' => 'bg-red-50 text-red-700 ring-red-600/20',
        'cancelled' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
    ];

    $trainingClosed = in_array(
        $training->status,
        ['completed', 'cancelled'],
        true
    );
@endphp

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="text-lg font-bold text-slate-800">
            My Registration
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Status pendaftaran akun kamu pada pelatihan ini.
        </p>
    </div>

    <div class="p-6">

        @if($myRegistration && in_array(
            $myRegistration->registration_status,
            ['registered', 'approved'],
            true
        ))
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <span class="inline-flex items-center rounded-full px-3 py-1.5
                                 text-xs font-semibold ring-1 ring-inset
                                 {{ $registrationStyles[$myRegistration->registration_status] }}">
                        {{ $registrationLabels[$myRegistration->registration_status] }}
                    </span>

                    <p class="mt-2 text-sm text-slate-500">
                        @if($myRegistration->registration_status === 'registered')
                            Pendaftaran kamu menunggu persetujuan admin.
                        @else
                            Pendaftaran kamu telah disetujui. Pastikan hadir sesuai jadwal.
                        @endif
                    </p>

                    @if($myRegistration->registration_status === 'approved')
                        <p class="mt-1 text-xs text-slate-500">
                            Kehadiran:
                            {{ ucfirst(str_replace('_', ' ', $myRegistration->attendance_status)) }}
                        </p>
                    @endif
                </div>

                @if(!$trainingClosed)
                    <form
                        method="POST"
                        action="{{ route('trainings.participants.cancel', $training) }}"
                        onsubmit="return confirm('Batalkan pendaftaran kamu pada pelatihan ini?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl
                                   border border-red-200 px-4 py-2.5 text-sm
                                   font-semibold text-red-600 transition
                                   hover:bg-red-50"
                        >
                            Cancel Registration
                        </button>
                    </form>
                @endif

            </div>

        @elseif($myRegistration &&
            $myRegistration->registration_status === 'rejected')
            <div class="rounded-xl border border-red-200 bg-red-50 p-4">
                <p class="text-sm font-semibold text-red-800">
                    Registration Rejected
                </p>
                <p class="mt-1 text-sm text-red-700">
                    Pendaftaran ini ditolak oleh admin. Hubungi admin apabila
                    kamu membutuhkan informasi lebih lanjut.
                </p>
            </div>

        @elseif(!$trainingClosed)
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-semibold text-slate-800">
                        Join this training
                    </p>
                    <p class="mt-1 text-sm text-slate-500">
                        Daftarkan diri untuk mengikuti pelatihan ini.
                        Pendaftaran akan menunggu persetujuan admin.
                    </p>
                </div>

                <form
                    method="POST"
                    action="{{ route('trainings.participants.store', $training) }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2
                               rounded-xl bg-blue-600 px-5 py-3 text-sm
                               font-semibold text-white shadow-sm transition
                               hover:bg-blue-700 focus:outline-none
                               focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Register Now
                    </button>
                </form>
            </div>

        @else
            <p class="text-sm text-slate-500">
                Pendaftaran sudah ditutup karena pelatihan selesai
                atau dibatalkan.
            </p>
        @endif

    </div>
</div>


{{-- =========================================================
     ADMIN: PARTICIPANT MANAGEMENT
========================================================== --}}

@if(auth()->user()->role === 'admin')

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-bold text-slate-800">
                Participant Management
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Kelola status pendaftaran dan kehadiran peserta.
            </p>
        </div>

        @if($training->participants->isEmpty())
            <div class="px-6 py-12 text-center">
                <p class="font-semibold text-slate-800">
                    No participants yet
                </p>
                <p class="mt-1 text-sm text-slate-500">
                    Peserta yang mendaftar akan muncul di sini.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold
                                       uppercase tracking-wider text-slate-500">
                                Employee
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold
                                       uppercase tracking-wider text-slate-500">
                                Registration
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold
                                       uppercase tracking-wider text-slate-500">
                                Attendance
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @foreach($training->participants as $participant)
                            <tr class="align-top">

                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $participant->user?->name ?? 'Unknown User' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        NIK:
                                        {{ $participant->user?->nik ?? '-' }}
                                    </p>
                                </td>

                                {{-- Registration status --}}
                                <td class="min-w-64 px-5 py-4">
                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'trainings.participants.registration.update',
                                            [$training, $participant]
                                        ) }}"
                                        class="space-y-2"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <select
                                            name="registration_status"
                                            aria-label="Registration status"
                                            class="w-full rounded-lg border border-slate-300
                                                   px-3 py-2 text-sm focus:border-blue-500
                                                   focus:outline-none focus:ring-2
                                                   focus:ring-blue-100"
                                        >
                                            @foreach($registrationLabels as $value => $label)
                                                <option
                                                    value="{{ $value }}"
                                                    @selected(
                                                        $participant->registration_status === $value
                                                    )
                                                >
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button
                                            type="submit"
                                            class="text-xs font-semibold text-blue-600
                                                   hover:text-blue-800"
                                        >
                                            Update Registration
                                        </button>
                                    </form>
                                </td>

                                {{-- Attendance status --}}
                                <td class="min-w-64 px-5 py-4">
                                    @if($participant->registration_status === 'approved')
                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'trainings.participants.attendance.update',
                                                [$training, $participant]
                                            ) }}"
                                            class="space-y-2"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <select
                                                name="attendance_status"
                                                aria-label="Attendance status"
                                                class="w-full rounded-lg border border-slate-300
                                                       px-3 py-2 text-sm focus:border-blue-500
                                                       focus:outline-none focus:ring-2
                                                       focus:ring-blue-100"
                                            >
                                                <option
                                                    value="not_marked"
                                                    @selected($participant->attendance_status === 'not_marked')
                                                >
                                                    Not Marked
                                                </option>

                                                <option
                                                    value="present"
                                                    @selected($participant->attendance_status === 'present')
                                                >
                                                    Present
                                                </option>

                                                <option
                                                    value="absent"
                                                    @selected($participant->attendance_status === 'absent')
                                                >
                                                    Absent
                                                </option>
                                            </select>

                                            <button
                                                type="submit"
                                                class="text-xs font-semibold text-blue-600
                                                       hover:text-blue-800"
                                            >
                                                Update Attendance
                                            </button>
                                        </form>
                                    @else
                                        <p class="text-xs text-slate-400">
                                            Approve registration first.
                                        </p>
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