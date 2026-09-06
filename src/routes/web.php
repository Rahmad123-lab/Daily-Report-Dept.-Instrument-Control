<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\DailyReportItemController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\MaintenanceHistoryController;
use App\Http\Controllers\DashboardController;

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

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');

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

    Route::resource(
        'daily-reports',
        DailyReportController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Daily Report Workflow
    |--------------------------------------------------------------------------
    */

    Route::post(
        'daily-reports/{dailyReport}/publish',
        [DailyReportController::class, 'publish']
    )->name('daily-reports.publish');


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


    /*
    |--------------------------------------------------------------------------
    | Equipment
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'equipment',
        EquipmentController::class
    );


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

});


require __DIR__.'/auth.php';