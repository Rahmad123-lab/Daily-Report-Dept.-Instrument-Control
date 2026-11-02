<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\MaintenanceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MaintenanceHistoryController extends Controller
{
    /**
     * Display the create maintenance history form.
     */
    public function create()
    {
        $equipment = Equipment::query()
            ->orderBy('equipment_name')
            ->get();

        return view(
            'maintenance-history.create',
            compact('equipment')
        );
    }

    /**
     * Store a new maintenance history.
     */
    public function store(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'maintenance_date' => [
                'required',
                'date',
            ],

            'maintenance_type' => [
                'required',
                Rule::in([
                    'Preventive',
                    'Corrective',
                    'Calibration',
                    'Inspection',
                    'Breakdown',
                ]),
            ],

            'status' => [
                'required',
                Rule::in([
                    'Open',
                    'Progress',
                    'Completed',
                ]),
            ],

            'technician' => [
                'required',
                'string',
                'max:255',
            ],

            'work_order_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'duration' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'result' => [
                'required',
                Rule::in([
                    'Completed',
                    'Pending',
                    'Failed',
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        // Completed status requires a Completed result.
        $this->validateCompletedStatus(
            $validated['status'],
            $validated['result']
        );

        // Normalize optional values.
        $validated['duration'] = $validated['duration'] ?? 0;
        $validated['created_by'] = Auth::id();

        $equipment->maintenanceHistories()->create($validated);

        return redirect()
            ->route('equipment.show', $equipment)
            ->with(
                'success',
                'Maintenance history berhasil ditambahkan.'
            );
    }

    /**
     * Display the edit maintenance history form.
     */
    public function edit(
        Equipment $equipment,
        MaintenanceHistory $history
    ) {
        $this->ensureHistoryBelongsToEquipment($equipment, $history);

        return view(
            'maintenance-history.edit',
            compact('equipment', 'history')
        );
    }

    /**
     * Update an existing maintenance history.
     */
    public function update(
        Request $request,
        Equipment $equipment,
        MaintenanceHistory $history
    ) {
        $this->ensureHistoryBelongsToEquipment($equipment, $history);

        $validated = $request->validate([
            'maintenance_date' => [
                'required',
                'date',
            ],

            'maintenance_type' => [
                'required',
                Rule::in([
                    'Preventive',
                    'Corrective',
                    'Calibration',
                    'Inspection',
                    'Breakdown',
                ]),
            ],

            'status' => [
                'required',
                Rule::in([
                    'Open',
                    'Progress',
                    'Completed',
                ]),
            ],

            'technician' => [
                'required',
                'string',
                'max:255',
            ],

            'work_order_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'duration' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'result' => [
                'required',
                Rule::in([
                    'Completed',
                    'Pending',
                    'Failed',
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        // Completed status requires a Completed result.
        $this->validateCompletedStatus(
            $validated['status'],
            $validated['result']
        );

        // Normalize optional values.
        $validated['duration'] = $validated['duration'] ?? 0;

        $history->update($validated);

        return redirect()
            ->route('equipment.show', $equipment)
            ->with(
                'success',
                'Maintenance history berhasil diperbarui.'
            );
    }

    /**
     * Delete a maintenance history.
     */
    public function destroy(
        Equipment $equipment,
        MaintenanceHistory $history
    ) {
        $this->ensureHistoryBelongsToEquipment($equipment, $history);

        $history->delete();

        return redirect()
            ->route('equipment.show', $equipment)
            ->with(
                'success',
                'Maintenance history berhasil dihapus.'
            );
    }

    /**
     * Validate the relationship between status and result.
     */
    private function validateCompletedStatus(
        string $status,
        string $result
    ): void {
        if (
            $status === 'Completed'
            && $result !== 'Completed'
        ) {
            throw ValidationException::withMessages([
                'result' => 'Status Completed hanya dapat digunakan jika hasil maintenance Completed.',
            ]);
        }
    }

    /**
     * Ensure the history belongs to the specified equipment.
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
