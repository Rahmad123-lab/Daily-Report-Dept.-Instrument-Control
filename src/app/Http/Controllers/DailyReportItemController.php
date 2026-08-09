<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\DailyReportItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DailyReportItemController extends Controller
{
    /**
     * Menyimpan activity baru
     */
    public function store(Request $request, DailyReport $dailyReport)
    {
        $validated = $request->validate([

            'category' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'spk_number' => 'nullable|string|max:255',
            'status' => 'required|in:Open,Progress,Done',

            'workers' => 'nullable|array',
            'workers.*' => 'exists:users,id',

        ]);

        $item = $dailyReport->items()->create([

            'category' => $validated['category'],

            'title' => $validated['title'],

            'description' => $validated['description'],

            'spk_number' => $validated['spk_number'] ?? null,

            'status' => $validated['status'],

        ]);

        if (!empty($validated['workers'])) {

            $item->workers()->sync(
                $validated['workers']
            );

        }

        return redirect()
            ->route('daily-reports.show', $dailyReport)
            ->with('success', 'Activity berhasil ditambahkan.');

    }


    /**
     * Menampilkan form edit activity
     */
    public function edit(DailyReport $dailyReport, DailyReportItem $item)
{
    $workers = User::orderBy('name')->get();

    return view(
        'daily-reports.items.edit',
        compact('dailyReport', 'item', 'workers')
    );
}


    /**
     * Update activity
     */
    public function update(
        Request $request,
        DailyReport $dailyReport,
        DailyReportItem $item
    ) {

        $validated = $request->validate([

            'category' => 'required',

            'title' => 'required|string|max:255',

            'description' => 'required|string',

            'spk_number' => 'nullable|string|max:255',

            'status' => 'required|in:Open,Progress,Done',

            'workers' => 'nullable|array',

            'workers.*' => 'exists:users,id',

        ]);


        $item->update([

            'category' => $validated['category'],

            'title' => $validated['title'],

            'description' => $validated['description'],

            'spk_number' => $validated['spk_number'] ?? null,

            'status' => $validated['status'],

        ]);


        $item->workers()->sync(
            $validated['workers'] ?? []
        );


        return redirect()
            ->route(
                'daily-reports.show',
                $dailyReport
            )
            ->with(
                'success',
                'Activity berhasil diperbarui.'
            );

    }


    /**
     * Delete activity
     */
    public function destroy(
        DailyReport $dailyReport,
        DailyReportItem $item
    ) {

        $item->workers()->detach();

        $item->delete();


        return redirect()
            ->route(
                'daily-reports.show',
                $dailyReport
            )
            ->with(
                'success',
                'Activity berhasil dihapus.'
            );

    }

}