<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\User;
use Illuminate\Http\Request;

class DailyReportController extends Controller
{
    /**
     * Menampilkan daftar Daily Report
     */
    public function index()
    {
        $reports = DailyReport::with('creator')
            ->when(request('search'), function ($query) {
                $query->where(function ($q) {
                    $q->where(
                        'report_number',
                        'like',
                        '%' . request('search') . '%'
                    )
                    ->orWhere(
                        'division',
                        'like',
                        '%' . request('search') . '%'
                    );
                });
            })
            ->when(request('date'), function ($query) {
                $query->whereDate(
                    'report_date',
                    request('date')
                );
            })
            ->when(request('shift'), function ($query) {
                $query->where(
                    'shift',
                    request('shift')
                );
            })
            ->when(request('status'), function ($query) {
                $query->where(
                    'status',
                    request('status')
                );
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'daily-reports.index',
            compact('reports')
        );
    }


    /**
     * Form membuat Daily Report
     */
    public function create()
    {
        $workers = User::orderBy('name')->get();

        return view(
            'daily-reports.create',
            compact('workers')
        );
    }


    /**
     * Menyimpan Daily Report
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'report_date' => [
                'required',
                'date',
            ],

            'shift' => [
                'required',
                'in:Non Shift,Piket Malam',
            ],

            'division' => [
                'required',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

        ]);


        DailyReport::create([

            'report_number' =>
                'DR-' .
                now()->format('Ymd') .
                '-' .
                rand(100, 999),

            'report_date' =>
                $validated['report_date'],

            'shift' =>
                $validated['shift'],

            'division' =>
                $validated['division'],

            'notes' =>
                $validated['notes'] ?? null,

            'created_by' =>
                auth()->id(),

            'status' =>
                'Draft',

        ]);


        return redirect()
            ->route('daily-reports.index')
            ->with(
                'success',
                'Daily Report berhasil dibuat.'
            );
    }


    /**
     * Menyimpan Work Activity
     */
    public function storeItem(
        Request $request,
        DailyReport $dailyReport
    ) {
        $validated = $request->validate([

            'category' => [
                'required',
                'in:Perintah Atasan,Pekerjaan Rutin,Buku Cacat,Preventive,Corrective,Emergency',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'spk_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'in:Open,Progress,Done',
            ],

            'workers' => [
                'nullable',
                'array',
            ],

            'workers.*' => [
                'exists:users,id',
            ],

        ]);


        $item = $dailyReport->items()->create([

            'category' =>
                $validated['category'],

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description'],

            'spk_number' =>
                $validated['spk_number'] ?? null,

            'status' =>
                $validated['status'],

        ]);


        if (!empty($validated['workers'])) {

            $item->workers()->sync(
                $validated['workers']
            );

        }


        return redirect()
            ->route(
                'daily-reports.show',
                $dailyReport
            )
            ->with(
                'success',
                'Work activity berhasil ditambahkan.'
            );
    }


    /**
     * Menampilkan detail Daily Report
     */
    public function show(DailyReport $dailyReport)
    {
        $dailyReport->load([
            'creator',
            'items.workers',
        ]);

        $workers = User::orderBy('name')->get();

        return view(
            'daily-reports.show',
            compact(
                'dailyReport',
                'workers'
            )
        );
    }


    /**
     * Form edit Daily Report
     */
    public function edit(DailyReport $dailyReport)
{
    if ($dailyReport->status === 'Published') {
        return redirect()
            ->route('daily-reports.show', $dailyReport)
            ->with(
                'error',
                'Daily Report yang sudah Published tidak dapat diedit.'
            );
    }

    return view(
        'daily-reports.edit',
        compact('dailyReport')
    );
}


    /**
     * Update Daily Report
     */
    public function update(
    Request $request,
    DailyReport $dailyReport
) {
    if ($dailyReport->status === 'Published') {
        return back()->with(
            'error',
            'Daily Report yang sudah Published tidak dapat diedit.'
        );
    }

    $validated = $request->validate([

        'report_date' => [
            'required',
            'date',
        ],

        'shift' => [
            'required',
            'in:Non Shift,Piket Malam',
        ],

        'division' => [
            'required',
            'string',
            'max:255',
        ],

        'notes' => [
            'nullable',
            'string',
        ],

    ]);

    $dailyReport->update($validated);

    return redirect()
        ->route(
            'daily-reports.show',
            $dailyReport
        )
        ->with(
            'success',
            'Daily Report berhasil diperbarui.'
        );
}

    /**
     * Hapus Daily Report
     */
   public function destroy(
    DailyReport $dailyReport
) {
    if ($dailyReport->status === 'Published') {
        return back()->with(
            'error',
            'Daily Report yang sudah Published tidak dapat dihapus.'
        );
    }

    $dailyReport->items->each(
        function ($item) {

            $item->workers()->detach();

            $item->delete();

        }
    );

    $dailyReport->delete();

    return redirect()
        ->route('daily-reports.index')
        ->with(
            'success',
            'Daily Report berhasil dihapus.'
        );
}

    /**
     * Publish Daily Report
     */
    public function publish(
        DailyReport $dailyReport
    ) {
        if ($dailyReport->status !== 'Draft') {

            return back()->with(
                'error',
                'Hanya report Draft yang dapat dipublish.'
            );

        }


        $dailyReport->update([

            'status' => 'Published',

        ]);


        return back()->with(
            'success',
            'Daily Report berhasil dipublish.'
        );
    }
}