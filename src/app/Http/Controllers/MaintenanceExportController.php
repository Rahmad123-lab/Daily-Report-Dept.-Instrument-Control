<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\MaintenanceHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MaintenanceExportController extends Controller
{
    /**
     * Display the maintenance PDF preview page.
     */
    public function preview(
        Equipment $equipment,
        MaintenanceHistory $history
    ): View {
        $this->ensureHistoryBelongsToEquipment($equipment, $history);

        return view(
            'maintenance-history.preview',
            compact('equipment', 'history')
        );
    }

    /**
     * Display PDF inline in the browser.
     */
    public function previewPdf(
        Equipment $equipment,
        MaintenanceHistory $history
    ): Response {
        [$pdf, $filename] = $this->buildPdf($equipment, $history);

        return $pdf->stream($filename);
    }

    /**
     * Download maintenance report as PDF.
     */
    public function export(
        Equipment $equipment,
        MaintenanceHistory $history
    ): Response {
        [$pdf, $filename] = $this->buildPdf($equipment, $history);

        return $pdf->download($filename);
    }

    /**
     * Build the shared maintenance PDF document.
     */
    private function buildPdf(
        Equipment $equipment,
        MaintenanceHistory $history
    ): array {
        $this->ensureHistoryBelongsToEquipment($equipment, $history);

        $history->load([
            'equipment',
            'creator:id,name,nik',
        ]);

        $pdf = Pdf::loadView(
            'maintenance-history.pdf.report',
            compact('equipment', 'history')
        )
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => false,
                'isPhpEnabled' => false,
            ]);

        $equipmentCode = Str::slug(
            $equipment->equipment_code,
            '-'
        );

        $reportFilename = Str::slug(
            $history->work_order_number ?: 'maintenance-' . $history->id,
            '-'
        );

        $filename = 'DRICS-Maintenance-'
            . ($equipmentCode ?: $equipment->id)
            . '-'
            . $reportFilename
            . '.pdf';

        return [$pdf, $filename];
    }

    /**
     * Prevent accessing a maintenance record through the wrong equipment.
     */
    private function ensureHistoryBelongsToEquipment(
        Equipment $equipment,
        MaintenanceHistory $history
    ): void {
        abort_unless(
            (int) $history->equipment_id === (int) $equipment->id,
            404
        );
    }
}
