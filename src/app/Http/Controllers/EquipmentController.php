<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EquipmentController extends Controller
{
    /**
     * Display a listing of equipment.
     */
    public function index(Request $request)
    {
        $equipment = Equipment::query()
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {

                    $search = $request->string('search')->trim();

                    $query->where(function ($q) use ($search) {

                        $q->where(
                            'equipment_code',
                            'like',
                            "%{$search}%"
                        )

                        ->orWhere(
                            'equipment_name',
                            'like',
                            "%{$search}%"
                        )

                        ->orWhere(
                            'tag_number',
                            'like',
                            "%{$search}%"
                        )

                        ->orWhere(
                            'manufacturer',
                            'like',
                            "%{$search}%"
                        )

                        ->orWhere(
                            'location',
                            'like',
                            "%{$search}%"
                        );

                    });
                }
            )

            ->when(
                $request->filled('category'),
                function ($query) use ($request) {

                    $query->where(
                        'category',
                        $request->category
                    );
                }
            )

            ->when(
                $request->filled('status'),
                function ($query) use ($request) {

                    $query->where(
                        'status',
                        $request->status
                    );
                }
            )

            ->orderBy('equipment_name')

            ->paginate(10)

            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Equipment Statistics
        |--------------------------------------------------------------------------
        */

        $stats = [

            'total' =>
                Equipment::count(),

            'active' =>
                Equipment::where(
                    'status',
                    'Active'
                )->count(),

            'standby' =>
                Equipment::where(
                    'status',
                    'Standby'
                )->count(),

            'maintenance' =>
                Equipment::where(
                    'status',
                    'Maintenance'
                )->count(),

            'breakdown' =>
                Equipment::where(
                    'status',
                    'Breakdown'
                )->count(),

            'decommission' =>
                Equipment::where(
                    'status',
                    'Decommission'
                )->count(),

        ];


        return view(
            'equipment.index',
            compact(
                'equipment',
                'stats'
            )
        );
    }


    /**
     * Show create form.
     */
    public function create()
    {
        return view(
            'equipment.create'
        );
    }


    /**
     * Store newly created equipment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'equipment_code' => [
                'required',
                'string',
                'max:50',
                'unique:equipments,equipment_code',
            ],

            'equipment_name' => [
                'required',
                'string',
                'max:255',
            ],

            'tag_number' => [
                'required',
                'string',
                'max:100',
                'unique:equipments,tag_number',
            ],

            'category' => [
                'required',
                'string',
                'max:100',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'manufacturer' => [
                'nullable',
                'string',
                'max:255',
            ],

            'model' => [
                'nullable',
                'string',
                'max:255',
            ],

            'serial_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'installation_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                Rule::in([
                    'Active',
                    'Standby',
                    'Maintenance',
                    'Breakdown',
                    'Decommission',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
            ],

        ]);


        $equipment = Equipment::create(
            $validated
        );


        return redirect()
            ->route(
                'equipment.show',
                $equipment
            )
            ->with(
                'success',
                'Equipment berhasil ditambahkan.'
            );
    }


    /**
     * Display equipment detail.
     */
    public function show(
        Equipment $equipment
    ) {

        $equipment->load([
            'maintenanceHistories' => function ($query) {

                $query
                    ->with('creator')
                    ->latest('maintenance_date');
            },
        ]);


        $histories =
            $equipment->maintenanceHistories;


        /*
        |--------------------------------------------------------------------------
        | Maintenance Summary
        |--------------------------------------------------------------------------
        */

       $summary = [
    'total' => $histories->count(),

    'last' => $histories->first(),

    'preventive' => $histories
        ->where('maintenance_type', 'Preventive')
        ->count(),

    'corrective' => $histories
        ->where('maintenance_type', 'Corrective')
        ->count(),

    'calibration' => $histories
        ->where('maintenance_type', 'Calibration')
        ->count(),

    'inspection' => $histories
        ->where('maintenance_type', 'Inspection')
        ->count(),
];


        return view(
            'equipment.show',
            compact(
                'equipment',
                'summary'
            )
        );
    }


    /**
     * Show edit form.
     */
    public function edit(
        Equipment $equipment
    ) {
        return view(
            'equipment.edit',
            compact('equipment')
        );
    }


    /**
     * Update equipment.
     */
    public function update(
        Request $request,
        Equipment $equipment
    ) {

        $validated = $request->validate([

            'equipment_code' => [
                'required',
                'string',
                'max:50',

                Rule::unique(
                    'equipments',
                    'equipment_code'
                )->ignore($equipment->id),
            ],

            'equipment_name' => [
                'required',
                'string',
                'max:255',
            ],

            'tag_number' => [
                'required',
                'string',
                'max:100',

                Rule::unique(
                    'equipments',
                    'tag_number'
                )->ignore($equipment->id),
            ],

            'category' => [
                'required',
                'string',
                'max:100',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'manufacturer' => [
                'nullable',
                'string',
                'max:255',
            ],

            'model' => [
                'nullable',
                'string',
                'max:255',
            ],

            'serial_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'installation_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                Rule::in([
                    'Active',
                    'Standby',
                    'Maintenance',
                    'Breakdown',
                    'Decommission',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
            ],

        ]);


        $equipment->update(
            $validated
        );


        return redirect()
            ->route(
                'equipment.show',
                $equipment
            )
            ->with(
                'success',
                'Equipment berhasil diperbarui.'
            );
    }


    /**
     * Delete equipment.
     */
    public function destroy(
        Equipment $equipment
    ) {

        /*
        |--------------------------------------------------------------------------
        | Prevent deletion when maintenance history exists
        |--------------------------------------------------------------------------
        */

        if (
            $equipment
                ->maintenanceHistories()
                ->exists()
        ) {

            return redirect()
                ->route(
                    'equipment.show',
                    $equipment
                )
                ->with(
                    'error',
                    'Equipment tidak dapat dihapus karena sudah memiliki maintenance history.'
                );
        }


        $equipment->delete();


        return redirect()
            ->route(
                'equipment.index'
            )
            ->with(
                'success',
                'Equipment berhasil dihapus.'
            );
    }
}