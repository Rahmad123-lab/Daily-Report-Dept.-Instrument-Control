<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <title>Training Evaluation Report</title>

    <style>
        @page {
            margin: 22px 28px 48px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #334155;
            line-height: 1.5;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .brand {
            font-size: 19px;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 1px;
        }

        .brand-description {
            color: #64748b;
            font-size: 8px;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 12px;
        }

        .report-subtitle {
            color: #64748b;
            font-size: 9px;
            margin-top: 3px;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
            margin-top: 17px;
            margin-bottom: 8px;
        }

        .training-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .training-info td {
            width: 50%;
            padding: 5px 7px;
            border: 1px solid #e2e8f0;
        }

        .label {
            color: #64748b;
            font-size: 8px;
        }

        .value {
            font-weight: bold;
            color: #1e293b;
        }

        .summary {
            width: 100%;
            border-collapse: separate;
            border-spacing: 7px;
            margin: 0 -7px;
        }

        .summary td {
            width: 25%;
            padding: 10px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }

        .summary-label {
            font-size: 8px;
            color: #64748b;
        }

        .summary-value {
            font-size: 19px;
            font-weight: bold;
            color: #1e40af;
            margin-top: 5px;
        }

        .summary-green {
            color: #15803d;
        }

        .summary-orange {
            color: #c2410c;
        }

        .filter-info {
            margin-top: 8px;
            color: #64748b;
            font-size: 8px;
        }

        .evaluation-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .evaluation-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            padding: 8px 5px;
            font-size: 8px;
            text-align: left;
            border: 1px solid #1e3a8a;
        }

        .evaluation-table td {
            padding: 7px 5px;
            border: 1px solid #e2e8f0;
            font-size: 8px;
            vertical-align: top;
        }

        .evaluation-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .evaluation-table {
            page-break-inside: auto;
        }

        .evaluation-table thead {
            display: table-header-group;
        }

        .evaluation-table tr {
            page-break-inside: avoid;
        }

        .center {
            text-align: center !important;
        }

        .employee-name {
            font-weight: bold;
            color: #0f172a;
        }

        .competency-name {
            font-weight: bold;
            color: #1e293b;
        }

        .muted {
            color: #64748b;
        }

        .badge-achieved {
            color: #15803d;
            font-weight: bold;
        }

        .badge-pending {
            color: #b45309;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            text-align: right;
            font-size: 8px;
            line-height: 1.5;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }

        .empty {
            padding: 25px;
            text-align: center;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .notes {
            margin-top: 12px;
            font-size: 8px;
            color: #64748b;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <div class="header">

        <div class="brand">
            DRICS
        </div>

        <div class="brand-description">
            Digital Reliability & Instrument Control System
            <br>
            Instrument & Control System Department
        </div>

        <div class="report-title">
            Training Evaluation Report
        </div>

        <div class="report-subtitle">
            Laporan hasil evaluasi kompetensi setelah training.
        </div>

    </div>


    {{-- Training Information --}}
    <div class="section-title">
        Training Information
    </div>

    <table class="training-info">
        <tr>
            <td>
                <div class="label">Training Name</div>
                <div class="value">{{ $training->title }}</div>
            </td>

            <td>
                <div class="label">Department / Organizer</div>
                <div class="value">{{ $training->organizer }}</div>
            </td>
        </tr>

        <tr>
            <td>
                <div class="label">Instructor</div>
                <div class="value">{{ $training->instructor }}</div>
            </td>

            <td>
                <div class="label">Location</div>
                <div class="value">{{ $training->location }}</div>
            </td>
        </tr>

        <tr>
            <td>
                <div class="label">Start Date</div>
                <div class="value">
                    {{ \Carbon\Carbon::parse($training->start_date)->format('d M Y') }}
                </div>
            </td>

            <td>
                <div class="label">End Date</div>
                <div class="value">
                    {{ \Carbon\Carbon::parse($training->end_date)->format('d M Y') }}
                </div>
            </td>
        </tr>
    </table>


    {{-- Active Filters --}}
    @if ($search !== '' || $category !== '')
        <div class="filter-info">
            Filter laporan:

            @if ($search !== '')
                Pencarian: {{ $search }}
            @endif

            @if ($category !== '')
                | Kategori: {{ $category }}
            @endif
        </div>
    @endif


    {{-- Summary --}}
    <div class="section-title">
        Evaluation Summary
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="summary-label">
                    Total Evaluations
                </div>

                <div class="summary-value">
                    {{ $totalEvaluations }}
                </div>
            </td>

            <td>
                <div class="summary-label">
                    Employees Evaluated
                </div>

                <div class="summary-value">
                    {{ $totalEmployees }}
                </div>
            </td>

            <td>
                <div class="summary-label">
                    Average Post Level
                </div>

                <div class="summary-value">
                    {{ $averagePostLevel !== null
                        ? number_format($averagePostLevel, 1)
                        : '-' }}
                </div>
            </td>

            <td>
                <div class="summary-label">
                    Target Achievement
                </div>

                <div class="summary-value summary-green">
                    {{ $targetAchievementRate }}%
                </div>
            </td>
        </tr>
    </table>


    {{-- Evaluation Details --}}
    <div class="section-title">
        Evaluation Details
    </div>

    @if ($evaluations->isEmpty())

        <div class="empty">
            Belum terdapat data evaluasi untuk laporan ini.
        </div>

    @else

        <table class="evaluation-table">

            <thead>
                <tr>
                    <th width="3%" class="center">No.</th>
                    <th width="15%">Employee</th>
                    <th width="20%">Competency</th>
                    <th width="8%" class="center">Pre Level</th>
                    <th width="8%" class="center">Post Level</th>
                    <th width="8%" class="center">Target</th>
                    <th width="8%" class="center">Improvement</th>
                    <th width="10%" class="center">Result</th>
                    <th width="10%">Assessment Date</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($evaluations as $index => $evaluation)

                    @php
                        $improvement = $evaluation->pre_training_level !== null
                            ? $evaluation->post_training_level
                                - $evaluation->pre_training_level
                            : null;

                        $targetReached = $evaluation->post_training_level
                            >= $evaluation->target_level;
                    @endphp

                    <tr>

                        <td class="center">
                            {{ $index + 1 }}
                        </td>

                        <td>
                            <div class="employee-name">
                                {{ $evaluation->employee_name }}
                            </div>

                            <div class="muted">
                                ID: {{ $evaluation->user_id }}
                            </div>
                        </td>

                        <td>
                            <div class="competency-name">
                                {{ $evaluation->competency_name }}
                            </div>

                            <div class="muted">
                                {{ $evaluation->competency_code }}
                            </div>

                            <div class="muted">
                                {{ $evaluation->competency_category }}
                            </div>
                        </td>

                        <td class="center">
                            {{ $evaluation->pre_training_level !== null
                                ? 'Level ' . $evaluation->pre_training_level
                                : '-' }}
                        </td>

                        <td class="center">
                            <strong>
                                Level {{ $evaluation->post_training_level }}
                            </strong>
                        </td>

                        <td class="center">
                            Level {{ $evaluation->target_level }}
                        </td>

                        <td class="center">

                            @if ($improvement === null)
                                No baseline
                            @elseif ($improvement > 0)
                                +{{ $improvement }}
                            @else
                                {{ $improvement }}
                            @endif

                        </td>

                        <td class="center">

                            @if ($targetReached)
                                <span class="badge-achieved">
                                    Achieved
                                </span>
                            @else
                                <span class="badge-pending">
                                    Not Achieved
                                </span>
                            @endif

                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($evaluation->assessed_at)->format('d M Y') }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @endif


    {{-- Achievement Summary --}}
    <div class="section-title">
        Target Achievement Summary
    </div>

    <table class="training-info">
        <tr>
            <td>
                <div class="label">Target Achieved</div>

                <div class="value" style="color: #15803d;">
                    {{ $targetAchieved }}
                </div>
            </td>

            <td>
                <div class="label">Target Not Achieved</div>

                <div class="value" style="color: #c2410c;">
                    {{ $targetNotAchieved }}
                </div>
            </td>
        </tr>
    </table>


    {{-- Footer --}}
   <div class="footer">
    Generated by DRICS on {{ now()->timezone('Asia/Jakarta')->format('d M Y H:i') }}
    <br>
    DRICS - Instrument &amp; Control System Department
</div>

</body>
</html>