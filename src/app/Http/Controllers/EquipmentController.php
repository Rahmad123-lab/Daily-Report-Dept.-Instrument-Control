<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $equipment = Equipment::query()

            ->when($request->search, function ($query) use ($request) {

                $query->where(function ($q) use ($request) {

                    $q->where('equipment_code', 'like', "%{$request->search}%")
                        ->orWhere('equipment_name', 'like', "%{$request->search}%")
                        ->orWhere('tag_number', 'like', "%{$request->search}%")
                        ->orWhere('manufacturer', 'like', "%{$request->search}%");

                });

            })

            ->when($request->category, function ($query) use ($request) {

                $query->where('category', $request->category);

            })

            ->when($request->status, function ($query) use ($request) {

                $query->where('status', $request->status);

            })

            ->orderBy('equipment_name')
            ->paginate(10)
            ->withQueryString();

        $stats = [

    'total'          => Equipment::count(),

    'active'         => Equipment::where('status', 'Active')->count(),

    'standby'        => Equipment::where('status', 'Standby')->count(),

    'maintenance'    => Equipment::where('status', 'Maintenance')->count(),

    'breakdown'      => Equipment::where('status', 'Breakdown')->count(),

    'decommission'   => Equipment::where('status', 'Decommission')->count(),

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
        return view('equipment.create');
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

        $equipment = Equipment::create($validated);

        return redirect()
            ->route('equipment.show', $equipment)
            ->with(
                'success',
                'Equipment berhasil ditambahkan.'
            );
    }

   /**
 * Display equipment detail.
 */
public function show(Equipment $equipment)
{
    $equipment->load([
        'maintenanceHistories' => function ($query) {
            $query->latest('maintenance_date');
        },
    ]);

    $histories = $equipment->maintenanceHistories;

    $summary = [

        'total' => $histories->count(),

        'last' => $histories->first(),

        'preventive' => $histories
            ->where('maintenance_type', 'Preventive')
            ->count(),

        'corrective' => $histories
            ->where('maintenance_type', 'Corrective')
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
    public function edit(Equipment $equipment)
    {
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

                Rule::unique('equipments', 'equipment_code')
                    ->ignore($equipment->id),

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

                Rule::unique('equipments', 'tag_number')
                    ->ignore($equipment->id),

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

        $equipment->update($validated);

        return redirect()
            ->route('equipment.show', $equipment)
            ->with(
                'success',
                'Equipment berhasil diperbarui.'
            );
    }

    /**
     * Delete equipment.
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return redirect()
            ->route('equipment.index')
            ->with(
                'success',
                'Equipment berhasil dihapus.'
            );
    }
}