<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceHistory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaintenanceController extends Controller
{
    /**
     * Display the maintenance dashboard.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Filters
        |--------------------------------------------------------------------------
        */
        $filters = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:100',
            ],
            'status' => [
                'nullable',
                Rule::in([
                    'Open',
                    'Progress',
                    'Completed',
                ]),
            ],
            'maintenance_type' => [
                'nullable',
                Rule::in([
                    'Preventive',
                    'Corrective',
                    'Calibration',
                    'Inspection',
                    'Breakdown',
                ]),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */
        $query = MaintenanceHistory::query()
            ->with([
                'equipment',
                'creator',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if (! empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function (Builder $builder) use ($search) {
                $builder
                    ->where('work_order_number', 'like', "%{$search}%")
                    ->orWhere('technician', 'like', "%{$search}%")
                    ->orWhereHas(
                        'equipment',
                        function (Builder $equipmentQuery) use ($search) {
                            $equipmentQuery
                                ->where(
                                    'equipment_name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'equipment_code',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'tag_number',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        /*
        |--------------------------------------------------------------------------
        | Maintenance Type Filter
        |--------------------------------------------------------------------------
        */
        if (! empty($filters['maintenance_type'])) {
            $query->where(
                'maintenance_type',
                $filters['maintenance_type']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        | Statistics cover all records, regardless of table filters.
        |--------------------------------------------------------------------------
        */
        $stats = [
            'total' => MaintenanceHistory::count(),

            'open' => MaintenanceHistory::where(
                'status',
                'Open'
            )->count(),

            'progress' => MaintenanceHistory::where(
                'status',
                'Progress'
            )->count(),

            'completed' => MaintenanceHistory::where(
                'status',
                'Completed'
            )->count(),

            'preventive' => MaintenanceHistory::where(
                'maintenance_type',
                'Preventive'
            )->count(),

            'corrective' => MaintenanceHistory::where(
                'maintenance_type',
                'Corrective'
            )->count(),

            'calibration' => MaintenanceHistory::where(
                'maintenance_type',
                'Calibration'
            )->count(),

            'inspection' => MaintenanceHistory::where(
                'maintenance_type',
                'Inspection'
            )->count(),

            'breakdown' => MaintenanceHistory::where(
                'maintenance_type',
                'Breakdown'
            )->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Maintenance Records
        |--------------------------------------------------------------------------
        */
        $maintenance = $query
            ->orderByDesc('maintenance_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */
        return view(
            'maintenance-history.index',
            compact('maintenance', 'stats')
        );
    }
}
