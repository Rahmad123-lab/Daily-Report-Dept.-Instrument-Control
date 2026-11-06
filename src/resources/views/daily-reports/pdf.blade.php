<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DRICS Daily Report - {{ $dailyReport->report_number }}</title>

    <style>
        /* =========================================
           PAGE CONFIGURATION
        ========================================= */

        @page {
            margin: 22px 26px 38px 26px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 8px;
            color: #1e293b;
            line-height: 1.35;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* =========================================
           HEADER
        ========================================= */

        .header-table td {
            vertical-align: middle;
            padding-bottom: 8px;
        }

        .brand {
            color: #1e40af;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .brand-subtitle {
            color: #64748b;
            font-size: 7px;
            letter-spacing: 1px;
        }

        .header-title {
            text-align: right;
        }

        .header-title h1 {
            margin: 0;
            color: #0f172a;
            font-size: 14px;
        }

        .header-title p {
            margin: 2px 0 0;
            color: #64748b;
            font-size: 7px;
        }

        .header-line {
            border: 0;
            border-top: 2px solid #1e40af;
            margin: 0 0 9px;
        }

        /* =========================================
           GENERAL SECTION
        ========================================= */

        .section {
            margin-top: 9px;
        }

        .section-title {
            margin: 0 0 6px;
            padding-bottom: 4px;
            border-bottom: 1px solid #cbd5e1;
            color: #1e3a8a;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            page-break-after: avoid;
        }

        .label {
            display: block;
            color: #64748b;
            font-size: 6.5px;
            text-transform: uppercase;
        }

        .value {
            display: block;
            margin-top: 2px;
            color: #0f172a;
            font-size: 8px;
            font-weight: bold;
        }

        .muted {
            color: #64748b;
        }

        /* =========================================
           REPORT INFORMATION
        ========================================= */

        .info-table td {
            width: 50%;
            padding: 3px 7px 3px 0;
            vertical-align: top;
        }

        /* =========================================
           ATTENDANCE SUMMARY
        ========================================= */

        .attendance-table {
            width: 100%;
            table-layout: fixed;
        }

        .attendance-table th,
        .attendance-table td {
            padding: 5px;
            border: 1px solid #dbe2ea;
            text-align: center;
        }

        .attendance-table th {
            background: #eff6ff;
            color: #1e3a8a;
            font-size: 7px;
        }

        .attendance-table td {
            font-size: 9px;
            font-weight: bold;
        }

        /* =========================================
           ATTENDANCE DETAILS
        ========================================= */

        .attendance-subtitle {
            margin: 8px 0 5px;
            color: #1e3a8a;
            font-size: 8px;
            font-weight: bold;
        }

        .attendance-detail-table {
            width: 100%;
            table-layout: fixed;
        }

        .attendance-detail-table thead {
            display: table-header-group;
        }

        .attendance-detail-table th {
            padding: 5px 6px;
            border: 1px solid #dbe2ea;
            background: #1e3a8a;
            color: #ffffff;
            font-size: 7px;
            text-align: left;
        }

        .attendance-detail-table td {
            padding: 5px 6px;
            border: 1px solid #dbe2ea;
            font-size: 7.5px;
            vertical-align: middle;
        }

        .attendance-detail-table tr:nth-child(even) {
            background: #f8fafc;
        }

        .attendance-status {
            color: #1e3a8a;
            font-weight: bold;
        }

        /* =========================================
           WORK ACTIVITY SUMMARY
        ========================================= */

        .summary-table {
            width: 100%;
            table-layout: fixed;
        }

        .summary-table td {
            width: 25%;
            padding: 6px;
            border: 1px solid #dbe2ea;
            background: #f8fafc;
            text-align: center;
            vertical-align: middle;
        }

        .summary-number {
            display: block;
            color: #0f172a;
            font-size: 14px;
            font-weight: bold;
            line-height: 17px;
        }

        .summary-label {
            display: block;
            margin-top: 2px;
            color: #64748b;
            font-size: 7px;
        }

        /* =========================================
           WORK ACTIVITIES - SIMPLE DIV LAYOUT
        ========================================= */

        .activity-section {
            margin-top: 10px;
        }

        .activity-entry {
    display: block;
    width: 100%;
    margin: 0 0 10px;
    padding: 0 0 8px;
    border-bottom: 1px solid #cbd5e1;

    /* Keep each activity together */
    page-break-inside: avoid;
}

        .activity-heading {
            margin: 0 0 5px;
            color: #1e3a8a;
            font-size: 9px;
            font-weight: bold;
        }

        .activity-description {
            margin: 0 0 5px;
            color: #64748b;
            font-size: 7.5px;
            white-space: pre-line;
            word-wrap: break-word;
        }

        .activity-line {
            margin: 3px 0;
            font-size: 7.5px;
            line-height: 1.4;
            word-wrap: break-word;
        }

        .activity-label {
            color: #64748b;
            font-weight: bold;
        }

        .activity-value {
            color: #1e293b;
        }

        .activity-status {
            color: #166534;
            font-weight: bold;
        }

        /* =========================================
           NOTES
        ========================================= */

        .notes-section {
            margin-top: 10px;
        }

        .notes {
            padding: 7px;
            border: 1px solid #dbe2ea;
            background: #f8fafc;
            min-height: 22px;
            white-space: pre-line;
            word-wrap: break-word;
        }

        /* =========================================
           SIGNATURES
        ========================================= */

        .signature-table {
            width: 100%;
            margin-top: 10px;
            page-break-inside: avoid;
        }

        .signature-table td {
            width: 50%;
            padding: 5px 8px;
            text-align: center;
            vertical-align: top;
        }

        .signature-space {
            height: 30px;
        }

        .signature-name {
            padding-top: 5px;
            border-top: 1px solid #64748b;
            font-size: 8px;
            font-weight: bold;
        }

        /* =========================================
           FOOTER
        ========================================= */

        .footer {
            position: fixed;
            bottom: -25px;
            left: 0;
            right: 0;
            height: 18px;
            padding-top: 4px;
            border-top: 1px solid #cbd5e1;
            color: #64748b;
            font-size: 6.5px;
        }

        .footer-left {
            float: left;
        }

        .footer-right {
            float: right;
        }

        .page-number:after {
            content: counter(page);
        }

        /* =========================================
           EMPTY STATE
        ========================================= */

        .empty {
            padding: 10px;
            border: 1px dashed #cbd5e1;
            color: #64748b;
            text-align: center;
        }

    </style>
</head>

<body>

@php
    $attendance = [
        'Total Employees' => $dailyReport->total_employee ?? 0,
        'Present' => $dailyReport->present_employee ?? 0,
        'Leave' => $dailyReport->leave_employee ?? 0,
        'Sick' => $dailyReport->sick_employee ?? 0,
        'Permission' => $dailyReport->permission_employee ?? 0,
    ];
@endphp


{{-- =========================================
     DOCUMENT HEADER
========================================= --}}

<table class="header-table">
    <tr>
        <td width="45%">
            <div class="brand">DRICS</div>

            <div class="brand-subtitle">
                RELIABILITY SYSTEM
            </div>

            <div class="muted" style="margin-top: 4px;">
                Digital Reliability & Instrument Control System
            </div>
        </td>

        <td width="55%" class="header-title">
            <h1>DAILY OPERATIONAL REPORT</h1>
            <p>INSTRUMENT & CONTROL SYSTEM DEPARTMENT</p>
            <p>POWER PLANT DIVISION</p>
        </td>
    </tr>
</table>

<hr class="header-line">


{{-- =========================================
     REPORT INFORMATION
========================================= --}}

<div class="section">

    <h2 class="section-title">
        Report Information
    </h2>

    <table class="info-table">
        <tr>
            <td>
                <span class="label">Report Number</span>
                <span class="value">
                    {{ $dailyReport->report_number }}
                </span>
            </td>

            <td>
                <span class="label">Report Date</span>
                <span class="value">
                    {{ $dailyReport->report_date?->format('d F Y') ?? '-' }}
                </span>
            </td>
        </tr>

        <tr>
            <td>
                <span class="label">Shift</span>
                <span class="value">
                    {{ $dailyReport->shift ?: '-' }}
                </span>
            </td>

            <td>
                <span class="label">Division</span>
                <span class="value">
                    {{ $dailyReport->division ?: '-' }}
                </span>
            </td>
        </tr>

        <tr>
            <td>
                <span class="label">Report Status</span>
                <span class="value">
                    {{ $dailyReport->status }}
                </span>
            </td>

            <td>
                <span class="label">Created By</span>
                <span class="value">
                    {{ $dailyReport->creator?->name ?? '-' }}

                    @if($dailyReport->creator?->nik)
                        ({{ $dailyReport->creator->nik }})
                    @endif
                </span>
            </td>
        </tr>

        <tr>
            <td>
                <span class="label">Night Duty Officer</span>
                <span class="value">
                    {{ $dailyReport->nightDutyUser?->name ?? '-' }}
                </span>
            </td>

            <td>
                <span class="label">Generated At</span>
                <span class="value">
                    {{ now()->format('d F Y H:i') }}
                </span>
            </td>
        </tr>
    </table>

</div>


{{-- =========================================
     EMPLOYEE ATTENDANCE
========================================= --}}

<div class="section">

    <h2 class="section-title">
        Employee Attendance Summary
    </h2>

    <table class="attendance-table">
        <thead>
            <tr>
                @foreach($attendance as $label => $value)
                    <th>{{ $label }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            <tr>
                @foreach($attendance as $value)
                    <td>{{ number_format($value) }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <h3 class="attendance-subtitle">
        Employee Attendance Details
    </h3>

    <table class="attendance-detail-table">
        <thead>
            <tr>
                <th width="7%">No.</th>
                <th width="43%">Employee Name</th>
                <th width="20%">NIK</th>
                <th width="30%">Attendance Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($dailyReport->attendances as $index => $record)
                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>
                        {{ $record->user?->name ?? '-' }}
                    </td>

                    <td>
                        {{ $record->user?->nik ?? '-' }}
                    </td>

                    <td>
                        <span class="attendance-status">
                            {{ $record->attendance_status }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">
                        No employee attendance recorded.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>


{{-- =========================================
     WORK ACTIVITY SUMMARY
========================================= --}}

<div class="section">

    <h2 class="section-title">
        Work Activity Summary
    </h2>

    <table class="summary-table">
        <tr>
            <td>
                <span class="summary-number">
                    {{ $activitySummary['total'] }}
                </span>

                <span class="summary-label">
                    Total Activities
                </span>
            </td>

            <td>
                <span class="summary-number">
                    {{ $activitySummary['open'] }}
                </span>

                <span class="summary-label">
                    Open
                </span>
            </td>

            <td>
                <span class="summary-number">
                    {{ $activitySummary['progress'] }}
                </span>

                <span class="summary-label">
                    Progress
                </span>
            </td>

            <td>
                <span class="summary-number">
                    {{ $activitySummary['done'] }}
                </span>

                <span class="summary-label">
                    Done
                </span>
            </td>
        </tr>
    </table>

</div>


{{-- =========================================
     WORK ACTIVITIES
========================================= --}}

<div class="section activity-section">

    <h2 class="section-title">
        Work Activities & Maintenance Tasks
    </h2>

    @forelse($dailyReport->items as $index => $item)

        @php
            $progress = max(
                0,
                min(100, (int) ($item->progress ?? 0))
            );

            $startDate = $item->start_date
                ? \Carbon\Carbon::parse($item->start_date)->format('d M Y')
                : '-';

            $endDate = $item->end_date
                ? \Carbon\Carbon::parse($item->end_date)->format('d M Y')
                : '-';

            $workerNames = $item->workers
                ->pluck('name')
                ->implode(', ');
        @endphp

        <div class="activity-entry">

            <p class="activity-heading">
                {{ $index + 1 }}.
                {{ $item->title }}

                <span class="activity-status">
                    | {{ $item->status }}
                </span>
            </p>

            @if($item->description)
                <p class="activity-description">
                    {{ $item->description }}
                </p>
            @endif

            <div class="activity-line">
                <span class="activity-label">Category:</span>
                <span class="activity-value">
                    {{ $item->category ?: '-' }}
                </span>
            </div>

            <div class="activity-line">
                <span class="activity-label">SPK:</span>
                {{ $item->spk_number ?: '-' }}

                &nbsp; | &nbsp;

                <span class="activity-label">DTK:</span>
                {{ $item->dtk_number ?: '-' }}
            </div>

            <div class="activity-line">
            <span class="activity-label">Progress:</span>
            {{ $progress }}%

            &nbsp; | &nbsp;

            <span class="activity-label">Continuation:</span>
            {{ $item->is_continue ? 'Continued' : 'No' }}

            &nbsp; | &nbsp;

            <span class="activity-label">Workers:</span>
            {{ $workerNames !== '' ? $workerNames : 'Not assigned' }}
        </div>

        </div>

    @empty

        <div class="empty">
            No work activities recorded for this report.
        </div>

    @endforelse

</div>


{{-- =========================================
     REPORT NOTES
========================================= --}}

<div class="section notes-section">

    <h2 class="section-title">
        Report Notes & Remarks
    </h2>

    <div class="notes">
        {{ $dailyReport->notes ?: 'No additional notes.' }}
    </div>

</div>


{{-- =========================================
     SIGNATURES
========================================= --}}

<table class="signature-table">
    <tr>
        <td>
            Prepared By

            <div class="signature-space"></div>

            <div class="signature-name">
                {{ $dailyReport->creator?->name ?? '________________________' }}
            </div>

            <div class="muted">
                Report Creator
            </div>
        </td>

        <td>
            Reviewed / Approved By

            <div class="signature-space"></div>

            <div class="signature-name">
                ______________________________
            </div>

            <div class="muted">
                Authorized Personnel
            </div>
        </td>
    </tr>
</table>


{{-- =========================================
     FOOTER
========================================= --}}

<div class="footer">

    <span class="footer-left">
        DRICS | Digital Reliability & Instrument Control System
    </span>

    <span class="footer-right">
        {{ $dailyReport->report_number }}
        · Page <span class="page-number"></span>
    </span>

</div>

</body>
</html>
