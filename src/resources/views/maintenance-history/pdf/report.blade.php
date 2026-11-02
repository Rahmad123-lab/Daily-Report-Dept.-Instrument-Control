<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DRICS Maintenance Report</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 30px 36px 48px 36px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: "DejaVu Sans", sans-serif;
            font-size: 9px;
            line-height: 1.45;
            color: #1e293b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .document-header td {
            vertical-align: middle;
            padding-bottom: 12px;
        }

        .brand {
            color: #1e40af;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1.5px;
        }

        .brand-subtitle {
            margin-top: 1px;
            color: #64748b;
            font-size: 7px;
            letter-spacing: 1.2px;
        }

        .brand-description {
            margin-top: 5px;
            color: #64748b;
            font-size: 7px;
        }

        .document-title {
            text-align: right;
        }

        .document-title h1 {
            margin: 0;
            color: #0f172a;
            font-size: 15px;
            font-weight: bold;
        }

        .document-title p {
            margin: 3px 0 0;
            color: #64748b;
            font-size: 7px;
        }

        .top-line {
            height: 3px;
            margin: 0 0 15px;
            border: 0;
            background: #1e40af;
        }

        .section {
            margin-top: 14px;
            page-break-inside: avoid;
        }

        .section-title {
            margin: 0 0 9px;
            padding-bottom: 5px;
            border-bottom: 1px solid #cbd5e1;
            color: #1e3a8a;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .info-table td {
            width: 50%;
            padding: 6px 12px 7px 0;
            vertical-align: top;
        }

        .label {
            display: block;
            color: #64748b;
            font-size: 7px;
            font-weight: bold;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .value {
            display: block;
            margin-top: 3px;
            color: #0f172a;
            font-size: 9px;
            font-weight: bold;
        }

        .status {
            display: inline-block;
            width: auto;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .status-open {
            background: #fef3c7;
            color: #92400e;
        }

        .status-progress {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-completed {
            background: #dcfce7;
            color: #166534;
        }

        .result-completed {
            color: #15803d;
        }

        .result-pending {
            color: #b45309;
        }

        .result-failed {
            color: #b91c1c;
        }

        .equipment-box {
            padding: 11px;
            border: 1px solid #dbe2ea;
            background: #f8fafc;
        }

        .equipment-name {
            margin-bottom: 8px;
            color: #0f172a;
            font-size: 12px;
            font-weight: bold;
        }

        .equipment-table td {
            width: 50%;
            padding: 5px 8px 5px 0;
            vertical-align: top;
        }

        .result-table td {
            width: 50%;
            padding: 9px 10px;
            border: 1px solid #dbe2ea;
            vertical-align: top;
        }

        .result-table td.full-width {
            width: 100%;
        }

        .notes {
            padding: 10px;
            border: 1px solid #dbe2ea;
            background: #f8fafc;
            color: #334155;
            font-size: 9px;
            line-height: 1.6;
            white-space: pre-line;
            overflow-wrap: break-word;
        }

        .reference {
            padding: 10px;
            border-left: 3px solid #2563eb;
            background: #eff6ff;
        }

        .muted {
            color: #64748b;
        }

        /*
        |--------------------------------------------------------------------------
        | Page Break
        |--------------------------------------------------------------------------
        */

        .page-break {
            page-break-before: always;
        }

        /*
        |--------------------------------------------------------------------------
        | Signature
        |--------------------------------------------------------------------------
        */

        .signature-section {
            margin-top: 24px;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            table-layout: fixed;
        }

        .signature-table td {
            width: 50%;
            padding: 7px 16px;
            text-align: center;
            vertical-align: top;
        }

        .signature-heading {
            color: #64748b;
            font-size: 8px;
            font-weight: bold;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            padding-top: 6px;
            border-top: 1px solid #64748b;
            color: #0f172a;
            font-size: 9px;
            font-weight: bold;
        }

        .signature-caption {
            margin-top: 3px;
            color: #64748b;
            font-size: 7px;
        }

        /*
        |--------------------------------------------------------------------------
        | Footer
        |--------------------------------------------------------------------------
        */

        .footer {
            position: fixed;
            right: 0;
            bottom: -29px;
            left: 0;
            height: 19px;
            padding-top: 5px;
            border-top: 1px solid #cbd5e1;
            color: #64748b;
            font-size: 7px;
        }

        .footer-table td {
            width: 50%;
            padding: 0;
        }

        .footer-right {
            text-align: right;
        }

        .page-number:after {
            content: counter(page);
        }
    </style>
</head>

<body>

@php
    $statusClass = match ($history->status) {
        'Open' => 'status-open',
        'Progress' => 'status-progress',
        'Completed' => 'status-completed',
        default => '',
    };

    $resultClass = match ($history->result) {
        'Completed' => 'result-completed',
        'Pending' => 'result-pending',
        'Failed' => 'result-failed',
        default => '',
    };
@endphp

{{-- =========================================================
     PAGE 1: REPORT & EQUIPMENT INFORMATION
========================================================== --}}

{{-- DOCUMENT HEADER --}}
<table class="document-header">
    <tr>
        <td width="48%">
            <div class="brand">DRICS</div>
            <div class="brand-subtitle">RELIABILITY SYSTEM</div>

            <div class="brand-description">
                Digital Reliability & Instrument Control System
            </div>
        </td>

        <td width="52%" class="document-title">
            <h1>MAINTENANCE REPORT</h1>
            <p>INSTRUMENT &amp; CONTROL SYSTEM DEPARTMENT</p>
            <p>POWER PLANT DIVISION</p>
        </td>
    </tr>
</table>

<hr class="top-line">

{{-- MAINTENANCE INFORMATION --}}
<div class="section">
    <h2 class="section-title">
        Maintenance Information
    </h2>

    <table class="info-table">
        <tr>
            <td>
                <span class="label">Report Reference</span>
                <span class="value">MNT-{{ $history->id }}</span>
            </td>

            <td>
                <span class="label">Work Order Number</span>
                <span class="value">
                    {{ $history->work_order_number ?: '-' }}
                </span>
            </td>
        </tr>

        <tr>
            <td>
                <span class="label">Maintenance Date</span>
                <span class="value">
                    {{ $history->maintenance_date?->format('d F Y') ?? '-' }}
                </span>
            </td>

            <td>
                <span class="label">Maintenance Type</span>
                <span class="value">
                    {{ $history->maintenance_type ?: '-' }}
                </span>
            </td>
        </tr>

        <tr>
            <td>
                <span class="label">Maintenance Status</span>
                <div style="margin-top: 4px;">
                    <span class="status {{ $statusClass }}">
                        {{ $history->status ?: '-' }}
                    </span>
                </div>
            </td>

            <td>
                <span class="label">Final Result</span>
                <span class="value {{ $resultClass }}">
                    {{ $history->result ?: '-' }}
                </span>
            </td>
        </tr>

        <tr>
            <td>
                <span class="label">Technician</span>
                <span class="value">
                    {{ $history->technician ?: '-' }}
                </span>
            </td>

            <td>
                <span class="label">Duration</span>
                <span class="value">
                    {{ $history->duration !== null
                        ? number_format((int) $history->duration) . ' minutes'
                        : '-' }}
                </span>
            </td>
        </tr>
    </table>
</div>

{{-- EQUIPMENT INFORMATION --}}
<div class="section">
    <h2 class="section-title">
        Equipment Information
    </h2>

    <div class="equipment-box">

        <div class="equipment-name">
            {{ $equipment->equipment_name ?: 'Unnamed Equipment' }}
        </div>

        <table class="equipment-table">
            <tr>
                <td>
                    <span class="label">Equipment Code</span>
                    <span class="value">
                        {{ $equipment->equipment_code ?: '-' }}
                    </span>
                </td>

                <td>
                    <span class="label">Tag Number</span>
                    <span class="value">
                        {{ $equipment->tag_number ?: '-' }}
                    </span>
                </td>
            </tr>

            <tr>
                <td>
                    <span class="label">Category</span>
                    <span class="value">
                        {{ $equipment->category ?: '-' }}
                    </span>
                </td>

                <td>
                    <span class="label">Location</span>
                    <span class="value">
                        {{ $equipment->location ?: '-' }}
                    </span>
                </td>
            </tr>

            <tr>
                <td>
                    <span class="label">Manufacturer</span>
                    <span class="value">
                        {{ $equipment->manufacturer ?: '-' }}
                    </span>
                </td>

                <td>
                    <span class="label">Model</span>
                    <span class="value">
                        {{ $equipment->model ?: '-' }}
                    </span>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <span class="label">Serial Number</span>
                    <span class="value">
                        {{ $equipment->serial_number ?: '-' }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
</div>

{{-- =========================================================
     PAGE 2: EXECUTION, NOTES & SIGNATURE
========================================================== --}}

<div class="page-break"></div>

<h2 class="section-title" style="margin-top: 0;">
    Maintenance Execution Result
</h2>

<table class="result-table">
    <tr>
        <td>
            <span class="label">Maintenance Type</span>
            <span class="value">
                {{ $history->maintenance_type ?: '-' }}
            </span>
        </td>

        <td>
            <span class="label">Final Result</span>
            <span class="value {{ $resultClass }}">
                {{ $history->result ?: '-' }}
            </span>
        </td>
    </tr>

    <tr>
        <td colspan="2" class="full-width">
            <span class="label">Execution Status</span>

            <div style="margin-top: 4px;">
                <span class="status {{ $statusClass }}">
                    {{ $history->status ?: '-' }}
                </span>
            </div>
        </td>
    </tr>
</table>

{{-- TECHNICAL NOTES --}}
<div class="section">
    <h2 class="section-title">
        Technical Notes &amp; Remarks
    </h2>

    <div class="notes">
        {{ $history->notes ?: 'No additional maintenance notes provided.' }}
    </div>
</div>

{{-- RECORD INFORMATION --}}
<div class="section">
    <h2 class="section-title">
        Record Information
    </h2>

    <div class="reference">
        <span class="label">Recorded By</span>

        <span class="value">
            {{ $history->creator?->name ?? '-' }}

            @if ($history->creator?->nik)
                ({{ $history->creator->nik }})
            @endif
        </span>

        <div class="muted" style="margin-top: 5px; font-size: 8px;">
            Generated: {{ now()->format('d F Y H:i') }}
        </div>
    </div>
</div>

{{-- SIGNATURE --}}
<div class="signature-section">
    <h2 class="section-title">
        Verification &amp; Approval
    </h2>

    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-heading">
                    PERFORMED BY
                </div>

                <div class="signature-space"></div>

                <div class="signature-name">
                    {{ $history->technician ?: '____________________' }}
                </div>

                <div class="signature-caption">
                    Maintenance Technician
                </div>
            </td>

            <td>
                <div class="signature-heading">
                    REVIEWED / APPROVED BY
                </div>

                <div class="signature-space"></div>

                <div class="signature-name">
                    &nbsp;
                </div>

                <div class="signature-caption">
                    Supervisor / Authorized Person
                </div>
            </td>
        </tr>
    </table>
</div>

{{-- FOOTER --}}
<div class="footer">
    <table class="footer-table">
        <tr>
            <td>
                DRICS | Digital Reliability &amp; Instrument Control System
            </td>

            <td class="footer-right">
                MNT-{{ $history->id }} &nbsp;|&nbsp;
                Page <span class="page-number"></span>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
