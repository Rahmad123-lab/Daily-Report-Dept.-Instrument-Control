<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\MaintenanceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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

        'performed_by' => [
            'required',
            'string',
            'max:255',
        ],

        'work_order' => [
            'nullable',
            'string',
            'max:100',
        ],

        'result' => [
            'nullable',
            'string',
            'max:255',
        ],

        'cost' => [
            'nullable',
            'numeric',
            'min:0',
        ],

        'description' => [
            'nullable',
            'string',
        ],

    ]);

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

        'maintenance_date'=>'required|date',

        'maintenance_type'=>'required|string|max:100',

        'performed_by'=>'required|string|max:255',

        'work_order'=>'nullable|string|max:100',

        'result'=>'nullable|string|max:255',

        'cost'=>'nullable|numeric|min:0',

        'description'=>'nullable|string',

    ]);

    $history->update($validated);

    return redirect()

        ->route('equipment.show',$equipment)

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

        ->route('equipment.show',$equipment)

        ->with(
            'success',
            'Maintenance history berhasil dihapus.'
        );
}
}