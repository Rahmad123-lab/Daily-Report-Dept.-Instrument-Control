<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DailyReportExportController extends Controller
{
    /**
     * Display the PDF preview page.
     */
    public function preview(DailyReport $dailyReport): View
    {
        return view(
            'daily-reports.preview',
            compact('dailyReport')
        );
    }

    /**
     * Display PDF inline in the browser.
     */
    public function previewPdf(
        DailyReport $dailyReport
    ): Response {
        [$pdf, $filename] = $this->buildPdf($dailyReport);

        return $pdf->stream($filename);
    }

    /**
     * Download the Daily Report PDF.
     */
    public function export(
        DailyReport $dailyReport
    ): Response {
        [$pdf, $filename] = $this->buildPdf($dailyReport);

        return $pdf->download($filename);
    }

    /**
     * Build the shared Daily Report PDF.
     *
     * Used by both preview and download.
     */
    private function buildPdf(
        DailyReport $dailyReport
    ): array {
        $dailyReport->load([
            'creator:id,name,nik',
            'nightDutyUser:id,name,nik',
            'attendances.user:id,name,nik',
            'items.workers:id,name,nik',
        ]);

        $activitySummary = [
            'total' => $dailyReport->items->count(),

            'open' => $dailyReport->items
                ->where('status', 'Open')
                ->count(),

            'progress' => $dailyReport->items
                ->where('status', 'Progress')
                ->count(),

            'done' => $dailyReport->items
                ->where('status', 'Done')
                ->count(),
        ];

        $pdf = Pdf::loadView(
            'daily-reports.pdf',
            compact(
                'dailyReport',
                'activitySummary'
            )
        )
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => false,
                'isPhpEnabled' => false,
            ]);

        $reportNumber = Str::slug(
            $dailyReport->report_number,
            '-'
        );

        $filename = 'DRICS-Daily-Report-'
            . ($reportNumber ?: $dailyReport->id)
            . '.pdf';

        return [$pdf, $filename];
    }
}
