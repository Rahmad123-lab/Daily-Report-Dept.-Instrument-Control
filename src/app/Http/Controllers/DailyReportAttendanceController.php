<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\DailyReportAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DailyReportAttendanceController extends Controller
{
    /**
     * Store employee attendance.
     */
    public function store(Request $request, DailyReport $dailyReport)
    {
        $this->ensureDraft($dailyReport);

        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('daily_report_attendances', 'user_id')
                    ->where('daily_report_id', $dailyReport->id),
            ],

            'attendance_status' => [
                'required',
                Rule::in([
                    'Present',
                    'Leave',
                    'Sick',
                    'Permission',
                ]),
            ],
        ]);

        DB::transaction(function () use ($dailyReport, $validated) {
            $dailyReport->attendances()->create($validated);

            $this->syncAttendanceSummary($dailyReport);
        });

        return redirect()
            ->route('daily-reports.show', $dailyReport)
            ->with('success', 'Employee attendance berhasil ditambahkan.');
    }

    /**
     * Update employee attendance status.
     */
    public function update(
        Request $request,
        DailyReport $dailyReport,
        DailyReportAttendance $attendance
    ) {
        $this->ensureDraft($dailyReport);
        $this->ensureAttendanceBelongsToReport($dailyReport, $attendance);

        $validated = $request->validate([
            'attendance_status' => [
                'required',
                Rule::in([
                    'Present',
                    'Leave',
                    'Sick',
                    'Permission',
                ]),
            ],
        ]);

        DB::transaction(function () use ($dailyReport, $attendance, $validated) {
            $attendance->update($validated);

            $this->syncAttendanceSummary($dailyReport);
        });

        return redirect()
            ->route('daily-reports.show', $dailyReport)
            ->with('success', 'Attendance berhasil diperbarui.');
    }

    /**
     * Delete employee attendance.
     */
    public function destroy(
        DailyReport $dailyReport,
        DailyReportAttendance $attendance
    ) {
        $this->ensureDraft($dailyReport);
        $this->ensureAttendanceBelongsToReport($dailyReport, $attendance);

        DB::transaction(function () use ($dailyReport, $attendance) {
            $attendance->delete();

            $this->syncAttendanceSummary($dailyReport);
        });

        return redirect()
            ->route('daily-reports.show', $dailyReport)
            ->with('success', 'Attendance berhasil dihapus.');
    }

    /**
     * Synchronize attendance summary columns in daily_reports.
     */
    private function syncAttendanceSummary(DailyReport $dailyReport): void
    {
        $summary = DB::table('daily_report_attendances')
            ->where('daily_report_id', $dailyReport->id)
            ->selectRaw('COUNT(*) AS total_employee')
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN attendance_status = ? THEN 1 ELSE 0 END), 0) AS present_employee',
                ['Present']
            )
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN attendance_status = ? THEN 1 ELSE 0 END), 0) AS leave_employee',
                ['Leave']
            )
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN attendance_status = ? THEN 1 ELSE 0 END), 0) AS sick_employee',
                ['Sick']
            )
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN attendance_status = ? THEN 1 ELSE 0 END), 0) AS permission_employee',
                ['Permission']
            )
            ->first();

        $dailyReport->forceFill([
            'total_employee' => (int) $summary->total_employee,
            'present_employee' => (int) $summary->present_employee,
            'leave_employee' => (int) $summary->leave_employee,
            'sick_employee' => (int) $summary->sick_employee,
            'permission_employee' => (int) $summary->permission_employee,
        ])->save();
    }

    /**
     * Attendance can only be changed while report is Draft.
     */
    private function ensureDraft(DailyReport $dailyReport): void
    {
        abort_unless(
            $dailyReport->status === 'Draft',
            403,
            'Attendance hanya dapat diubah pada Daily Report berstatus Draft.'
        );
    }

    /**
     * Prevent attendance from another report being modified.
     */
    private function ensureAttendanceBelongsToReport(
        DailyReport $dailyReport,
        DailyReportAttendance $attendance
    ): void {
        abort_unless(
            (int) $attendance->daily_report_id === (int) $dailyReport->id,
            404
        );
    }
}
