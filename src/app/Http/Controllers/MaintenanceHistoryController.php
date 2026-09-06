<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\MaintenanceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceHistoryController extends Controller
{
    /**
     * Store maintenance history
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
                'string',
                'max:100',
            ],

            'technician' => [
                'required',
                'string',
                'max:255',
            ],

            'work_order_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'duration' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'result' => [
                'nullable',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

        ]);

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
     * Edit form
     */
    public function edit(
        Equipment $equipment,
        MaintenanceHistory $history
    ) {
        return view(
            'maintenance-history.edit',
            compact(
                'equipment',
                'history'
            )
        );
    }


    /**
     * Update maintenance history
     */
    public function update(
        Request $request,
        Equipment $equipment,
        MaintenanceHistory $history
    ) {
        $validated = $request->validate([

            'maintenance_date' => [
                'required',
                'date',
            ],

            'maintenance_type' => [
                'required',
                'string',
                'max:100',
            ],

            'technician' => [
                'required',
                'string',
                'max:255',
            ],

            'work_order_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'duration' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'result' => [
                'nullable',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

        ]);

        $history->update($validated);

        return redirect()
            ->route('equipment.show', $equipment)
            ->with(
                'success',
                'Maintenance history berhasil diperbarui.'
            );
    }


    /**
     * Delete maintenance history
     */
    public function destroy(
        Equipment $equipment,
        MaintenanceHistory $history
    ) {
        $history->delete();

        return redirect()
            ->route('equipment.show', $equipment)
            ->with(
                'success',
                'Maintenance history berhasil dihapus.'
            );
    }
}