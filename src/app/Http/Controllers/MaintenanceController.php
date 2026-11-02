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
        | Equipment tetap di-load untuk menghindari N+1 query.
        | Creator tidak di-load karena tidak digunakan pada halaman index.
        |--------------------------------------------------------------------------
        */
        $query = MaintenanceHistory::query()
            ->with('equipment');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        $search = trim((string) ($filters['search'] ?? ''));

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($search) {
                $builder
                    ->where(
                        'work_order_number',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'technician',
                        'like',
                        "%{$search}%"
                    )
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
            $query->where(
                'status',
                $filters['status']
            );
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
        | Semua statistik dihitung dalam satu query agregasi.
        | Statistik tidak terpengaruh filter tabel.
        |--------------------------------------------------------------------------
        */
        $statsRow = MaintenanceHistory::query()
            ->selectRaw('COUNT(*) AS total')

            ->selectRaw(
                'COALESCE(SUM(CASE WHEN status = ? THEN 1 ELSE 0 END), 0) AS open',
                ['Open']
            )

            ->selectRaw(
                'COALESCE(SUM(CASE WHEN status = ? THEN 1 ELSE 0 END), 0) AS progress',
                ['Progress']
            )

            ->selectRaw(
                'COALESCE(SUM(CASE WHEN status = ? THEN 1 ELSE 0 END), 0) AS completed',
                ['Completed']
            )

            ->selectRaw(
                'COALESCE(SUM(CASE WHEN maintenance_type = ? THEN 1 ELSE 0 END), 0) AS preventive',
                ['Preventive']
            )

            ->selectRaw(
                'COALESCE(SUM(CASE WHEN maintenance_type = ? THEN 1 ELSE 0 END), 0) AS corrective',
                ['Corrective']
            )

            ->selectRaw(
                'COALESCE(SUM(CASE WHEN maintenance_type = ? THEN 1 ELSE 0 END), 0) AS calibration',
                ['Calibration']
            )

            ->selectRaw(
                'COALESCE(SUM(CASE WHEN maintenance_type = ? THEN 1 ELSE 0 END), 0) AS inspection',
                ['Inspection']
            )

            ->selectRaw(
                'COALESCE(SUM(CASE WHEN maintenance_type = ? THEN 1 ELSE 0 END), 0) AS breakdown',
                ['Breakdown']
            )

            ->first();

        /*
         * Cast aggregate values to integers.
         * Keep the same keys used by the Blade view.
         */
        $stats = [
            'total' => (int) ($statsRow->total ?? 0),
            'open' => (int) ($statsRow->open ?? 0),
            'progress' => (int) ($statsRow->progress ?? 0),
            'completed' => (int) ($statsRow->completed ?? 0),
            'preventive' => (int) ($statsRow->preventive ?? 0),
            'corrective' => (int) ($statsRow->corrective ?? 0),
            'calibration' => (int) ($statsRow->calibration ?? 0),
            'inspection' => (int) ($statsRow->inspection ?? 0),
            'breakdown' => (int) ($statsRow->breakdown ?? 0),
        ];

        /*
        |--------------------------------------------------------------------------
        | Maintenance Records
        |--------------------------------------------------------------------------
        | Paginate 10 records per page.
        | Preserve filters when navigating pagination.
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
