<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\DailyReportItemController;
use App\Http\Controllers\DailyReportExportController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MaintenanceHistoryController;
use App\Http\Controllers\MaintenanceExportController;
use App\Http\Controllers\UserApprovalController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\TrainingParticipantController;
use App\Http\Controllers\TrainingRecommendationController;
use App\Http\Controllers\CompetencyController;
use App\Http\Controllers\EmployeeCompetencyController;
use App\Http\Controllers\CompetencyDashboardController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');


/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| All dashboard users must be authenticated and active.
|
*/

Route::middleware(['auth', 'active'])->group(function () {

    Route::get('/dashboard', [
        DashboardController::class,
        'index',
    ])->name('dashboard');

});


/*
|--------------------------------------------------------------------------
| Admin Management Routes
|--------------------------------------------------------------------------
|
| Requirements:
| - Authenticated
| - Active account
| - Admin role
|
*/

Route::middleware(['auth', 'active', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | User Approval Management
        |--------------------------------------------------------------------------
        */

        Route::get('/user-approvals', [
            UserApprovalController::class,
            'index',
        ])->name('user-approvals.index');

        Route::post('/user-approvals/{user}/approve', [
            UserApprovalController::class,
            'approve',
        ])->name('user-approvals.approve');

        Route::post('/user-approvals/{user}/reject', [
            UserApprovalController::class,
            'reject',
        ])->name('user-approvals.reject');


        /*
        |--------------------------------------------------------------------------
        | Employee Management
        |--------------------------------------------------------------------------
        */

        Route::resource('employees', EmployeeController::class)
            ->only([
                'index',
                'show',
                'edit',
                'update',
            ]);

        Route::patch('/employees/{employee}/status', [
            EmployeeController::class,
            'updateStatus',
        ])->whereNumber('employee')
          ->name('employees.status.update');

    });


/*
|--------------------------------------------------------------------------
| Authenticated Application Routes
|--------------------------------------------------------------------------
|
| All routes inside this group require:
| - Authentication
| - Active account
|
*/

Route::middleware(['auth', 'active'])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Profile Management
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [
        ProfileController::class,
        'edit',
    ])->name('profile.edit');

    Route::patch('/profile', [
        ProfileController::class,
        'update',
    ])->name('profile.update');

    Route::delete('/profile', [
        ProfileController::class,
        'destroy',
    ])->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | Maintenance Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/maintenance', [
        MaintenanceController::class,
        'index',
    ])->name('maintenance.index');


    /*
    |--------------------------------------------------------------------------
    | Daily Report Management
    |--------------------------------------------------------------------------
    */

    Route::resource('daily-reports', DailyReportController::class);

    Route::post('/daily-reports/{dailyReport}/publish', [
        DailyReportController::class,
        'publish',
    ])->name('daily-reports.publish');


    /*
    |--------------------------------------------------------------------------
    | Daily Report Items
    |--------------------------------------------------------------------------
    */

    Route::post('/daily-reports/{dailyReport}/items', [
        DailyReportItemController::class,
        'store',
    ])->name('daily-reports.items.store');

    Route::get(
        '/daily-reports/{dailyReport}/items/{item}/edit',
        [DailyReportItemController::class, 'edit']
    )->name('daily-reports.items.edit');

    Route::put(
        '/daily-reports/{dailyReport}/items/{item}',
        [DailyReportItemController::class, 'update']
    )->name('daily-reports.items.update');

    Route::delete(
        '/daily-reports/{dailyReport}/items/{item}',
        [DailyReportItemController::class, 'destroy']
    )->name('daily-reports.items.destroy');


    /*
    |--------------------------------------------------------------------------
    | Daily Report Preview & PDF Export
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/daily-reports/{daily_report}/preview',
        [DailyReportExportController::class, 'preview']
    )->whereNumber('daily_report')
     ->name('daily-reports.preview');

    Route::get(
        '/daily-reports/{daily_report}/preview/pdf',
        [DailyReportExportController::class, 'previewPdf']
    )->whereNumber('daily_report')
     ->name('daily-reports.preview.pdf');

    Route::get(
        '/daily-reports/{daily_report}/export/pdf',
        [DailyReportExportController::class, 'export']
    )->whereNumber('daily_report')
     ->name('daily-reports.export.pdf');


    /*
    |--------------------------------------------------------------------------
    | Equipment Management
    |--------------------------------------------------------------------------
    */

    Route::resource('equipment', EquipmentController::class);


    /*
    |--------------------------------------------------------------------------
    | Maintenance History Management
    |--------------------------------------------------------------------------
    */

    Route::get('/maintenance/create', [
        MaintenanceHistoryController::class,
        'create',
    ])->name('maintenance.create');

    Route::post(
        '/equipment/{equipment}/maintenance-history',
        [MaintenanceHistoryController::class, 'store']
    )->whereNumber('equipment')
     ->name('equipment.maintenance-history.store');

    Route::get(
        '/equipment/{equipment}/maintenance-history/{history}/edit',
        [MaintenanceHistoryController::class, 'edit']
    )->whereNumber('equipment')
     ->whereNumber('history')
     ->name('equipment.maintenance-history.edit');

    Route::put(
        '/equipment/{equipment}/maintenance-history/{history}',
        [MaintenanceHistoryController::class, 'update']
    )->whereNumber('equipment')
     ->whereNumber('history')
     ->name('equipment.maintenance-history.update');

    Route::delete(
        '/equipment/{equipment}/maintenance-history/{history}',
        [MaintenanceHistoryController::class, 'destroy']
    )->whereNumber('equipment')
     ->whereNumber('history')
     ->name('equipment.maintenance-history.destroy');


    /*
    |--------------------------------------------------------------------------
    | Maintenance PDF Preview & Export
    |--------------------------------------------------------------------------
    |
    | All three routes are protected by auth and active middleware.
    | The controller explicitly verifies equipment/history ownership.
    |
    */

    Route::get(
        '/equipment/{equipment}/maintenance-history/{history}/preview',
        [MaintenanceExportController::class, 'preview']
    )->whereNumber('equipment')
     ->whereNumber('history')
     ->name('maintenance-history.preview');

    Route::get(
        '/equipment/{equipment}/maintenance-history/{history}/preview/pdf',
        [MaintenanceExportController::class, 'previewPdf']
    )->whereNumber('equipment')
     ->whereNumber('history')
     ->name('maintenance-history.preview.pdf');

    Route::get(
        '/equipment/{equipment}/maintenance-history/{history}/export/pdf',
        [MaintenanceExportController::class, 'export']
    )->whereNumber('equipment')
     ->whereNumber('history')
     ->name('maintenance-history.export.pdf');

});


/*
|--------------------------------------------------------------------------
| Training Management
|--------------------------------------------------------------------------
|
| All users:
| - View training
| - Register for training
| - Cancel own registration
|
| Admin:
| - Create and manage training
| - Approve participants
| - Manage attendance
|
*/

Route::middleware(['auth', 'active'])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Training List
    |--------------------------------------------------------------------------
    */

    Route::get('/trainings', [
        TrainingController::class,
        'index',
    ])->name('trainings.index');


    /*
    |--------------------------------------------------------------------------
    | Training Participant Registration
    |--------------------------------------------------------------------------
    */

    Route::post('/trainings/{training}/participants', [
        TrainingParticipantController::class,
        'store',
    ])->whereNumber('training')
      ->name('trainings.participants.store');

    Route::delete('/trainings/{training}/participants/me', [
        TrainingParticipantController::class,
        'cancel',
    ])->whereNumber('training')
      ->name('trainings.participants.cancel');


    /*
    |--------------------------------------------------------------------------
    | Admin Training Management
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        Route::get('/trainings/create', [
            TrainingController::class,
            'create',
        ])->name('trainings.create');

        Route::post('/trainings', [
            TrainingController::class,
            'store',
        ])->name('trainings.store');

        Route::get('/trainings/{training}/edit', [
            TrainingController::class,
            'edit',
        ])->whereNumber('training')
          ->name('trainings.edit');

        Route::put('/trainings/{training}', [
            TrainingController::class,
            'update',
        ])->whereNumber('training')
          ->name('trainings.update');

        Route::delete('/trainings/{training}', [
            TrainingController::class,
            'destroy',
        ])->whereNumber('training')
          ->name('trainings.destroy');


        /*
        |--------------------------------------------------------------------------
        | Participant Registration Approval
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/trainings/{training}/participants/{participant}/registration',
            [TrainingParticipantController::class, 'updateRegistrationStatus']
        )->whereNumber('training')
         ->whereNumber('participant')
         ->name('trainings.participants.registration.update');


        /*
        |--------------------------------------------------------------------------
        | Participant Attendance Management
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/trainings/{training}/participants/{participant}/attendance',
            [TrainingParticipantController::class, 'updateAttendanceStatus']
        )->whereNumber('training')
         ->whereNumber('participant')
         ->name('trainings.participants.attendance.update');

    });


    /*
    |--------------------------------------------------------------------------
    | Training Details
    |--------------------------------------------------------------------------
    */

    Route::get('/trainings/{training}', [
        TrainingController::class,
        'show',
    ])->whereNumber('training')
      ->name('trainings.show');

});


/*
|--------------------------------------------------------------------------
| Training Recommendations
|--------------------------------------------------------------------------
|
| Active authenticated employees see their own recommendations.
| Admins can see recommendations for all active employees.
|
*/

Route::middleware(['auth', 'active'])->group(function () {

    Route::get('/training-recommendations', [
        TrainingRecommendationController::class,
        'index',
    ])->name('training-recommendations.index');

});


/*
|--------------------------------------------------------------------------
| Competency Management
|--------------------------------------------------------------------------
|
| General users:
| - View competency catalog
| - View competency assessment page
|
| Admin:
| - Create, update and delete competency master
| - Manage employee assessments
| - View competency gap dashboard
|
*/

Route::middleware(['auth', 'active'])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Competency Master List
    |--------------------------------------------------------------------------
    */

    Route::get('/competencies', [
        CompetencyController::class,
        'index',
    ])->name('competencies.index');


    /*
    |--------------------------------------------------------------------------
    | Employee Competency Records
    |--------------------------------------------------------------------------
    */

    Route::get('/employee-competencies', [
        EmployeeCompetencyController::class,
        'index',
    ])->name('employee-competencies.index');


    /*
    |--------------------------------------------------------------------------
    | Admin Competency Management
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {


        /*
        |--------------------------------------------------------------------------
        | Competency Gap Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/competencies/dashboard', [
            CompetencyDashboardController::class,
            'index',
        ])->name('competencies.dashboard');


        /*
        |--------------------------------------------------------------------------
        | Competency Master CRUD
        |--------------------------------------------------------------------------
        */

        Route::get('/competencies/create', [
            CompetencyController::class,
            'create',
        ])->name('competencies.create');

        Route::post('/competencies', [
            CompetencyController::class,
            'store',
        ])->name('competencies.store');

        Route::get('/competencies/{competency}/edit', [
            CompetencyController::class,
            'edit',
        ])->whereNumber('competency')
          ->name('competencies.edit');

        Route::put('/competencies/{competency}', [
            CompetencyController::class,
            'update',
        ])->whereNumber('competency')
          ->name('competencies.update');

        Route::delete('/competencies/{competency}', [
            CompetencyController::class,
            'destroy',
        ])->whereNumber('competency')
          ->name('competencies.destroy');


        /*
        |--------------------------------------------------------------------------
        | Employee Competency Assignment
        |--------------------------------------------------------------------------
        */

        Route::post('/employee-competencies', [
            EmployeeCompetencyController::class,
            'store',
        ])->name('employee-competencies.store');


        /*
        |--------------------------------------------------------------------------
        | Employee Competency Assessment Update
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/employee-competencies/{employeeCompetency}',
            [EmployeeCompetencyController::class, 'update']
        )->whereNumber('employeeCompetency')
         ->name('employee-competencies.update');


        /*
        |--------------------------------------------------------------------------
        | Employee Competency Deletion
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/employee-competencies/{employeeCompetency}',
            [EmployeeCompetencyController::class, 'destroy']
        )->whereNumber('employeeCompetency')
         ->name('employee-competencies.destroy');

    });


    /*
    |--------------------------------------------------------------------------
    | Competency Details
    |--------------------------------------------------------------------------
    */

    Route::get('/competencies/{competency}', [
        CompetencyController::class,
        'show',
    ])->whereNumber('competency')
      ->name('competencies.show');

});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Laravel Breeze authentication routes.
|
*/

require __DIR__ . '/auth.php';
