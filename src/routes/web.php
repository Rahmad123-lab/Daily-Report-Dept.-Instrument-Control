<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\DailyReportItemController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\MaintenanceHistoryController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | Daily Report
    |--------------------------------------------------------------------------
    */

    Route::resource('daily-reports', DailyReportController::class);

    Route::resource('equipment',EquipmentController::class);
    /*
|--------------------------------------------------------------------------
| Maintenance History
|--------------------------------------------------------------------------
*/

Route::post(
    'equipment/{equipment}/maintenance-history',
    [MaintenanceHistoryController::class, 'store']
)->name('equipment.maintenance-history.store');

Route::get(
    'equipment/{equipment}/maintenance-history/{history}/edit',
    [MaintenanceHistoryController::class, 'edit']
)->name('equipment.maintenance-history.edit');

Route::put(
    'equipment/{equipment}/maintenance-history/{history}',
    [MaintenanceHistoryController::class, 'update']
)->name('equipment.maintenance-history.update');

Route::delete(
    'equipment/{equipment}/maintenance-history/{history}',
    [MaintenanceHistoryController::class, 'destroy']
)->name('equipment.maintenance-history.destroy');


    /*
    |--------------------------------------------------------------------------
    | Daily Report Workflow
    |--------------------------------------------------------------------------
    */

    Route::post(
        'daily-reports/{dailyReport}/submit',
        [DailyReportController::class, 'submit']
    )->name('daily-reports.submit');

    Route::post(
        'daily-reports/{dailyReport}/approve',
        [DailyReportController::class, 'approve']
    )->name('daily-reports.approve');

    Route::post(
        'daily-reports/{dailyReport}/reject',
        [DailyReportController::class, 'reject']
    )->name('daily-reports.reject');


    /*
    |--------------------------------------------------------------------------
    | Daily Report Items
    |--------------------------------------------------------------------------
    */

    Route::post(
        'daily-reports/{dailyReport}/items',
        [DailyReportItemController::class, 'store']
    )->name('daily-reports.items.store');

    Route::get(
        'daily-reports/{dailyReport}/items/{item}/edit',
        [DailyReportItemController::class, 'edit']
    )->name('daily-reports.items.edit');

    Route::put(
        'daily-reports/{dailyReport}/items/{item}',
        [DailyReportItemController::class, 'update']
    )->name('daily-reports.items.update');

    Route::delete(
        'daily-reports/{dailyReport}/items/{item}',
        [DailyReportItemController::class, 'destroy']
    )->name('daily-reports.items.destroy');

});

require __DIR__.'/auth.php';