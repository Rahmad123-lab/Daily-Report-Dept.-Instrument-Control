<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyReport;
use App\Models\User;
use App\Models\DailyReportItem;

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
                    $q->where('report_number', 'like', '%' . request('search') . '%')
                        ->orWhere('division', 'like', '%' . request('search') . '%');
                });
            })
            ->when(request('date'), function ($query) {
                $query->whereDate('report_date', request('date'));
            })
            ->when(request('shift'), function ($query) {
                $query->where('shift', request('shift'));
            })
            ->when(request('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('daily-reports.index', compact('reports'));
    }

    /**
     * Form membuat Daily Report
     */
    public function create()
    {
        $workers = User::orderBy('name')->get();

        return view('daily-reports.create', compact('workers'));
    }

    /**
     * Menyimpan Daily Report
     */
    public function store(Request $request)
    {
        $request->validate([
            'shift' => 'required|in:Non Shift,Piket Malam',
            'division' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        DailyReport::create([
            'report_number' => 'DR-' . date('Ymd') . '-' . rand(100, 999),

            'report_date' => $request->report_date,

            'shift' => $request->shift,

            'division' => $request->division,

            'notes' => $request->notes,

            'created_by' => auth()->id(),

            'status' => 'Draft',
        ]);

        return redirect()
            ->route('daily-reports.index')
            ->with('success', 'Daily Report berhasil dibuat.');
    }
   public function storeItem(
    Request $request,
    DailyReport $dailyReport
) {
    $validated = $request->validate([

        'category' => [
            'required',
            'in:Perintah Atasan,Pekerjaan Rutin,Buku Catat,Preventive,Corrective,Emergency',
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

        'category' => $validated['category'],

        'title' => $validated['title'],

        'description' => $validated['description'],

        'spk_number' => $validated['spk_number'] ?? null,

        'status' => $validated['status'],

    ]);


    if (!empty($validated['workers'])) {

        $item->workers()->attach(
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
// public function show(DailyReport $dailyReport)
// {
//     return view('daily-reports.show', compact('dailyReport'));
// }

    /**
     * Form edit Daily Report
     */
    public function edit(DailyReport $dailyReport)
{
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
    $validated = $request->validate([

        'report_date' => 'required|date',

        'shift' => [
            'required',
            Rule::in([
                'Non Shift',
                'Piket Malam',
            ]),
        ],

        'division' => 'required|string|max:255',

        'notes' => 'nullable|string',

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
    $dailyReport->items->each(function ($item) {

        $item->workers()->detach();

        $item->delete();

    });

    $dailyReport->delete();

    return redirect()
        ->route('daily-reports.index')
        ->with(
            'success',
            'Daily Report berhasil dihapus.'
        );
}
public function submit(
    DailyReport $dailyReport
) {
    if ($dailyReport->status !== 'Draft') {

        return back()->with(
            'error',
            'Hanya report Draft yang dapat disubmit.'
        );

    }

    $dailyReport->update([

        'status' => 'Submitted',

    ]);

    return back()->with(
        'success',
        'Daily Report berhasil disubmit.'
    );
}
public function approve(
    DailyReport $dailyReport
) {
    if ($dailyReport->status !== 'Submitted') {

        return back()->with(
            'error',
            'Hanya report Submitted yang dapat di-approve.'
        );

    }

    $dailyReport->update([

        'status' => 'Approved',

    ]);

    return back()->with(
        'success',
        'Daily Report berhasil di-approve.'
    );
}
public function reject(
    DailyReport $dailyReport
) {
    if ($dailyReport->status !== 'Submitted') {

        return back()->with(
            'error',
            'Hanya report Submitted yang dapat direject.'
        );

    }

    $dailyReport->update([

        'status' => 'Rejected',

    ]);

    return back()->with(
        'success',
        'Daily Report dikembalikan.'
    );
}
}