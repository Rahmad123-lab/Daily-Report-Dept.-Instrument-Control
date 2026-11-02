
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>DRICS Daily Report - {{ $dailyReport->report_number }}</title>

    <style>
        @page {
            margin: 25px 28px 42px 28px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 9px;
            color: #1e293b;
            line-height: 1.45;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
            padding-bottom: 12px;
        }

        .brand {
            color: #1e40af;
            font-size: 21px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .brand-subtitle {
            color: #64748b;
            font-size: 8px;
            letter-spacing: 1px;
        }

        .header-title {
            text-align: right;
        }

        .header-title h1 {
            margin: 0;
            font-size: 15px;
            color: #0f172a;
        }

        .header-title p {
            margin: 3px 0 0;
            color: #64748b;
            font-size: 8px;
        }

        .header-line {
            border: 0;
            border-top: 2px solid #1e40af;
            margin: 0 0 15px;
        }

        .section {
            margin-top: 15px;
        }

        .section-title {
            margin: 0 0 8px;
            padding-bottom: 6px;
            border-bottom: 1px solid #cbd5e1;
            color: #1e3a8a;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .info-table td {
            width: 50%;
            padding: 5px 8px 5px 0;
            vertical-align: top;
        }

        .label {
            display: block;
            color: #64748b;
            font-size: 7px;
            text-transform: uppercase;
        }

        .value {
            display: block;
            margin-top: 2px;
            color: #0f172a;
            font-size: 9px;
            font-weight: bold;
        }

        .attendance-table th,
        .attendance-table td {
            padding: 8px;
            border: 1px solid #dbe2ea;
            text-align: center;
        }

        .attendance-table th {
            background: #eff6ff;
            color: #1e3a8a;
            font-size: 8px;
        }

        .attendance-table td {
            font-size: 10px;
            font-weight: bold;
        }

                
        .summary-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 8px;
            page-break-inside: avoid;
        }

        .summary-table tr {
            height: 52px;
            page-break-inside: avoid;
        }

        .summary-table td {
            width: 25%;
            height: 52px;
            padding: 6px;
            border: 1px solid #dbe2ea;
            background: #f8fafc;
            text-align: center;
            vertical-align: middle;
            overflow: hidden;
        }

        .summary-number {
            display: block;
            margin: 0;
            padding: 0;
            color: #0f172a;
            font-size: 15px;
            font-weight: bold;
            line-height: 18px;
        }

        .summary-label {
            display: block;
            margin-top: 3px;
            color: #64748b;
            font-size: 7px;
            line-height: 10px;
        }


        .activity-table {
            table-layout: fixed;
        }

        .activity-table thead {
            display: table-header-group;
        }

        .activity-table tr {
            page-break-inside: avoid;
        }

        .activity-table th {
            padding: 8px 6px;
            background: #1e3a8a;
            color: #ffffff;
            font-size: 7px;
            text-align: left;
            text-transform: uppercase;
        }

        .activity-table td {
            padding: 8px 6px;
            border: 1px solid #dbe2ea;
            vertical-align: top;
            font-size: 8px;
            word-wrap: break-word;
        }

        .activity-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .activity-title {
            color: #0f172a;
            font-weight: bold;
        }

        .muted {
            color: #64748b;
        }
        .section-title {
            page-break-after: avoid;
        }

        .activity-table tr {
            page-break-inside: avoid;
        }

        .signature-table {
            page-break-inside: avoid;
        }

        .status {
            display: inline-block;
            padding: 3px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }

        .status-open {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-progress {
            background: #fef3c7;
            color: #92400e;
        }

        .status-done {
            background: #dcfce7;
            color: #166534;
        }

        .notes {
            padding: 10px;
            border: 1px solid #dbe2ea;
            background: #f8fafc;
            min-height: 35px;
            white-space: pre-line;
        }

        .signature-table {
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .signature-table td {
            width: 50%;
            padding: 8px;
            text-align: center;
            vertical-align: top;
        }

        .signature-space {
            height: 55px;
        }

        .signature-name {
            padding-top: 5px;
            border-top: 1px solid #64748b;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: -27px;
            left: 0;
            right: 0;
            height: 20px;
            padding-top: 5px;
            border-top: 1px solid #cbd5e1;
            color: #64748b;
            font-size: 7px;
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

        .empty {
            padding: 15px;
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

{{-- =====================================================
     DOCUMENT HEADER
====================================================== --}}

<table class="header-table">
    <tr>
        <td width="45%">
            <div class="brand">DRICS</div>
            <div class="brand-subtitle">RELIABILITY SYSTEM</div>
            <div class="muted" style="margin-top: 5px;">
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


{{-- =====================================================
     REPORT INFORMATION
====================================================== --}}

<div class="section">

    <h2 class="section-title">Report Information</h2>

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
                    {{ $dailyReport->shift }}
                </span>
            </td>

            <td>
                <span class="label">Division</span>
                <span class="value">
                    {{ $dailyReport->division }}
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


{{-- =====================================================
     EMPLOYEE ATTENDANCE
====================================================== --}}

<div class="section">

    <h2 class="section-title">Employee Attendance Summary</h2>

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

</div>


{{-- =====================================================
     WORK ACTIVITY SUMMARY
====================================================== --}}

<div class="section">

    <h2 class="section-title">Work Activity Summary</h2>

    <table class="summary-table">
        <tr>
            <td>
                <span class="summary-number">
                    {{ $activitySummary['total'] }}
                </span>
                <span class="summary-label">Total Activities</span>
            </td>

            <td>
                <span class="summary-number">
                    {{ $activitySummary['open'] }}
                </span>
                <span class="summary-label">Open</span>
            </td>

            <td>
                <span class="summary-number">
                    {{ $activitySummary['progress'] }}
                </span>
                <span class="summary-label">Progress</span>
            </td>

            <td>
                <span class="summary-number">
                    {{ $activitySummary['done'] }}
                </span>
                <span class="summary-label">Done</span>
            </td>
        </tr>
    </table>

</div>


{{-- =====================================================
     WORK ACTIVITIES
====================================================== --}}

<div class="section">

    <h2 class="section-title">Work Activities & Maintenance Tasks</h2>

    @if($dailyReport->items->isEmpty())

        <div class="empty">
            No work activities recorded for this report.
        </div>

    @else

        <table class="activity-table">

            <thead>
                <tr>
                    <th width="4%">No.</th>
                    <th width="23%">Activity</th>
                    <th width="12%">Category</th>
                    <th width="14%">SPK / DTK</th>
                    <th width="16%">Period</th>
                    <th width="12%">Workers</th>
                    <th width="9%">Progress</th>
                    <th width="10%">Status</th>
                </tr>
            </thead>

            <tbody>

                @foreach($dailyReport->items as $index => $item)

                    @php
                        $progress = max(
                            0,
                            min(100, (int) ($item->progress ?? 0))
                        );

                        $statusClass = match ($item->status) {
                            'Open' => 'status-open',
                            'Progress' => 'status-progress',
                            'Done' => 'status-done',
                            default => '',
                        };
                    @endphp

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>
                            <div class="activity-title">
                                {{ $item->title }}
                            </div>

                            @if($item->description)
                                <div class="muted" style="margin-top: 4px;">
                                    {{ $item->description }}
                                </div>
                            @endif
                        </td>

                        <td>{{ $item->category }}</td>

                        <td>
                            <div>
                                SPK:
                                {{ $item->spk_number ?: '-' }}
                            </div>

                            <div style="margin-top: 3px;">
                                DTK:
                                {{ $item->dtk_number ?: '-' }}
                            </div>
                        </td>

                        <td>
                            @if($item->start_date)
                                {{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }}
                            @else
                                -
                            @endif

                            <div class="muted" style="margin-top: 3px;">
                                to
                            </div>

                            @if($item->end_date)
                                {{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            @forelse($item->workers as $worker)
                                <div style="margin-bottom: 3px;">
                                    {{ $worker->name }}
                                </div>
                            @empty
                                <span class="muted">Not assigned</span>
                            @endforelse
                        </td>

                        <td>
                            {{ $progress }}%
                            <div class="muted" style="margin-top: 3px;">
                                {{ $item->is_continue ? 'Continued' : '—' }}
                            </div>
                        </td>

                        <td>
                            <span class="status {{ $statusClass }}">
                                {{ $item->status }}
                            </span>
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @endif

</div>


{{-- =====================================================
     REPORT NOTES
====================================================== --}}

<div class="section">

    <h2 class="section-title">Report Notes & Remarks</h2>

    <div class="notes">
        {{ $dailyReport->notes ?: 'No additional notes.' }}
    </div>

</div>


{{-- =====================================================
     SIGNATURES
====================================================== --}}

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


{{-- =====================================================
     FOOTER
====================================================== --}}

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
