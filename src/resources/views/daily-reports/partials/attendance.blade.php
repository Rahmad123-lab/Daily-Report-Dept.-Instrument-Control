
@php
    /*
    |--------------------------------------------------------------------------
    | Attendance Data
    |--------------------------------------------------------------------------
    */

    $attendances = $dailyReport->attendances ?? collect();

    $attendedUserIds = $attendances
        ->pluck('user_id')
        ->all();

    $availableWorkers = $workers->reject(
        fn ($worker) => in_array(
            $worker->id,
            $attendedUserIds
        )
    );

    /*
    |--------------------------------------------------------------------------
    | Attendance Summary
    |--------------------------------------------------------------------------
    | Menggunakan detail attendance sebagai sumber data utama.
    |--------------------------------------------------------------------------
    */

    $attendanceSummary = [
        'total' => $attendances->count(),

        'present' => $attendances
            ->where('attendance_status', 'Present')
            ->count(),

        'leave' => $attendances
            ->where('attendance_status', 'Leave')
            ->count(),

        'sick' => $attendances
            ->where('attendance_status', 'Sick')
            ->count(),

        'permission' => $attendances
            ->where('attendance_status', 'Permission')
            ->count(),
    ];

    $attendanceCards = [
        [
            'label' => 'Total Employees',
            'value' => $attendanceSummary['total'],
            'style' => 'bg-slate-50 text-slate-800',
        ],
        [
            'label' => 'Present',
            'value' => $attendanceSummary['present'],
            'style' => 'bg-emerald-50 text-emerald-700',
        ],
        [
            'label' => 'Leave',
            'value' => $attendanceSummary['leave'],
            'style' => 'bg-amber-50 text-amber-700',
        ],
        [
            'label' => 'Sick',
            'value' => $attendanceSummary['sick'],
            'style' => 'bg-red-50 text-red-700',
        ],
        [
            'label' => 'Permission',
            'value' => $attendanceSummary['permission'],
            'style' => 'bg-blue-50 text-blue-700',
        ],
    ];
@endphp

<section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h2 class="text-xl font-bold text-slate-800">
                Employee Attendance
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Pencatatan kehadiran employee berdasarkan Daily Report.
            </p>
        </div>

        <span class="inline-flex w-fit items-center rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
            {{ $attendanceSummary['total'] }} Employees Recorded
        </span>

    </div>

    {{-- =====================================================
         VALIDATION MESSAGES
    ====================================================== --}}

    @if ($errors->any())
        <div class="m-5 rounded-xl border border-red-200 bg-red-50 p-4">
            <p class="text-sm font-semibold text-red-700">
                Attendance validation failed
            </p>

            <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- =====================================================
         ATTENDANCE SUMMARY
    ====================================================== --}}

    <div class="grid grid-cols-2 gap-3 p-5 sm:grid-cols-3 lg:grid-cols-5">

        @foreach ($attendanceCards as $card)
            <div class="rounded-xl p-4 {{ $card['style'] }}">

                <p class="text-xs font-medium">
                    {{ $card['label'] }}
                </p>

                <p class="mt-2 text-2xl font-bold tabular-nums">
                    {{ $card['value'] }}
                </p>

            </div>
        @endforeach

    </div>

    {{-- =====================================================
         ADD EMPLOYEE ATTENDANCE
    ====================================================== --}}

    @if ($dailyReport->status === 'Draft')

        <div class="border-y border-slate-200 bg-slate-50 p-5 sm:p-6">

            <div class="mb-5">
                <h3 class="font-semibold text-slate-800">
                    Add Employee Attendance
                </h3>

                <p class="mt-1 text-xs text-slate-500">
                    Pilih employee dan tentukan status kehadirannya.
                </p>
            </div>

            @if ($availableWorkers->isNotEmpty())

                <form
                    method="POST"
                    action="{{ route('daily-reports.attendances.store', $dailyReport) }}"
                    class="grid grid-cols-1 items-end gap-4 md:grid-cols-3"
                >

                    @csrf

                    {{-- Employee --}}
                    <div>
                        <label
                            for="attendance_user_id"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Employee
                        </label>

                        <select
                            id="attendance_user_id"
                            name="user_id"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="">
                                Select Employee
                            </option>

                            @foreach ($availableWorkers as $worker)
                                <option
                                    value="{{ $worker->id }}"
                                    @selected(old('user_id') == $worker->id)
                                >
                                    {{ $worker->name }}
                                    @if ($worker->nik)
                                        — {{ $worker->nik }}
                                    @endif
                                </option>
                            @endforeach

                        </select>

                        @error('user_id')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Attendance Status --}}
                    <div>
                        <label
                            for="attendance_status"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Attendance Status
                        </label>

                        <select
                            id="attendance_status"
                            name="attendance_status"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >
                            @foreach (['Present', 'Leave', 'Sick', 'Permission'] as $status)
                                <option
                                    value="{{ $status }}"
                                    @selected(old('attendance_status', 'Present') === $status)
                                >
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>

                        @error('attendance_status')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div>
                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            <span>+</span>
                            Add Attendance
                        </button>
                    </div>

                </form>

            @else

                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                    <p class="text-sm font-semibold text-emerald-700">
                        All available employees have been recorded.
                    </p>

                    <p class="mt-1 text-xs text-emerald-600">
                        Tidak ada employee lain yang belum memiliki catatan attendance pada laporan ini.
                    </p>
                </div>

            @endif

        </div>

    @endif

    {{-- =====================================================
         ATTENDANCE RECORDS
    ====================================================== --}}

    <div class="overflow-x-auto">

        <table class="w-full min-w-[650px] text-left text-sm">

            <thead class="border-b border-slate-200 bg-slate-50">

                <tr class="text-xs font-semibold uppercase tracking-wide text-slate-500">

                    <th class="px-6 py-4">
                        Employee
                    </th>

                    <th class="px-6 py-4">
                        NIK
                    </th>

                    <th class="px-6 py-4">
                        Attendance Status
                    </th>

                    @if ($dailyReport->status === 'Draft')
                        <th class="px-6 py-4 text-right">
                            Action
                        </th>
                    @endif

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-100">

                @forelse ($attendances as $attendance)

                    @php
                        $attendanceStyle = match ($attendance->attendance_status) {
                            'Present' => 'bg-emerald-50 text-emerald-700',
                            'Leave' => 'bg-amber-50 text-amber-700',
                            'Sick' => 'bg-red-50 text-red-700',
                            'Permission' => 'bg-blue-50 text-blue-700',
                            default => 'bg-slate-100 text-slate-600',
                        };
                    @endphp

                    <tr class="transition hover:bg-slate-50">

                        {{-- Employee --}}
                        <td class="px-6 py-4">

                            <p class="font-semibold text-slate-800">
                                {{ $attendance->user?->name ?? 'Employee unavailable' }}
                            </p>

                        </td>

                        {{-- NIK --}}
                        <td class="px-6 py-4 text-slate-500">
                            {{ $attendance->user?->nik ?? '-' }}
                        </td>

                        {{-- Attendance Status --}}
                        <td class="px-6 py-4">

                            @if ($dailyReport->status === 'Draft')

                                <form
                                    method="POST"
                                    action="{{ route('daily-reports.attendances.update', [$dailyReport, $attendance]) }}"
                                    class="flex items-center gap-2"
                                >

                                    @csrf
                                    @method('PUT')

                                    <select
                                        name="attendance_status"
                                        aria-label="Attendance status for {{ $attendance->user?->name ?? 'employee' }}"
                                        class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                    >

                                        @foreach (['Present', 'Leave', 'Sick', 'Permission'] as $status)

                                            <option
                                                value="{{ $status }}"
                                                @selected($attendance->attendance_status === $status)
                                            >
                                                {{ $status }}
                                            </option>

                                        @endforeach

                                    </select>

                                    <button
                                        type="submit"
                                        class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-700"
                                    >
                                        Save
                                    </button>

                                </form>

                            @else

                                <span class="inline-flex rounded-lg px-3 py-1.5 text-xs font-semibold {{ $attendanceStyle }}">
                                    {{ $attendance->attendance_status }}
                                </span>

                            @endif

                        </td>

                        {{-- Action --}}
                        @if ($dailyReport->status === 'Draft')

                            <td class="px-6 py-4 text-right">

                                <form
                                    method="POST"
                                    action="{{ route('daily-reports.attendances.destroy', [$dailyReport, $attendance]) }}"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus attendance employee ini?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </td>

                        @endif

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="{{ $dailyReport->status === 'Draft' ? 4 : 3 }}"
                            class="px-6 py-14 text-center"
                        >

                            <div class="mx-auto max-w-sm">

                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                                    <svg
                                        class="h-6 w-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M14 3.13a4 4 0 0 1 0 7.75M14 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"
                                        />
                                    </svg>
                                </div>

                                <p class="mt-3 text-sm font-semibold text-slate-700">
                                    No attendance records
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    Belum ada data employee attendance untuk Daily Report ini.
                                </p>

                            </div>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Footer --}}
    <div class="border-t border-slate-100 bg-slate-50 px-6 py-3">

        <p class="text-xs text-slate-500">
            Attendance records:
            <span class="font-semibold text-slate-700">
                {{ $attendanceSummary['total'] }}
            </span>
            employee(s)
        </p>

    </div>

</section>
