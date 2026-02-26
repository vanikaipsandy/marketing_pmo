<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Auth\SsoController;
use App\Http\Controllers\CompanyProfile\CompanyController;
use App\Http\Controllers\DigitalInitiative\DigitalInitiativeController;
use App\Http\Controllers\DigitalInitiative\TrsDigitalInitiativeController;
use App\Http\Controllers\InitiativeRelation\InitiativeRelationController;
use App\Http\Controllers\ITInitiative\CharterController;
use App\Http\Controllers\ITInitiative\ITInitiativeController;
use App\Http\Controllers\ITInitiative\MilestoneController;
use App\Http\Controllers\MasterData\MasterDataController;
use App\Http\Controllers\ProgramImplementation\DashboardController;
use App\Http\Controllers\ProgramImplementation\ProgramImplementationController;
use App\Http\Controllers\ProgramPlanning\DashboardController as PlanningDashboardController;
use App\Http\Controllers\ProgramPlanning\ProgramDefinition\DigitalInitiatives\IndexController as ProgramDefinitionDigitalInitiativesController;
use App\Http\Controllers\ProgramPlanning\ProgramDefinition\ITInitiatives\IndexController as ProgramDefinitionITInitiativesController;
use App\Http\Controllers\ProgramPlanning\ProgramPlanningController;
use App\Http\Controllers\ProgramPlanning\ProgramDefinition\IndexController as ProgramDefinitionController;
use App\Http\Controllers\Roadmap\RoadmapController;
use App\Http\Controllers\StrategicPillar\StrategicPillarController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [SsoController::class, 'showLogin'])->name('login');
    Route::get('/auth/google', [SsoController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [SsoController::class, 'handleGoogleCallback']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (any status)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/logout', [SsoController::class, 'logout'])->name('logout');

});

/*
|--------------------------------------------------------------------------
| Approved User Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/dashboard-monitoring', PlanningDashboardController::class)->name('dashboard-monitoring');
    Route::get('/program-planning/rsti-sub-holding', [ProgramPlanningController::class, 'rstiSubHolding'])->name('program-planning.rsti-sub-holding');
    Route::get('/program-planning/program-definition', ProgramDefinitionController::class)->name('program-planning.program-definition');
    Route::get('/program-planning/program-definition/digital-initiatives', ProgramDefinitionDigitalInitiativesController::class)->name('program-planning.program-definition.digital-initiatives');
    Route::get('/program-planning/program-definition/it-initiatives', ProgramDefinitionITInitiativesController::class)->name('program-planning.program-definition.it-initiatives');
    Route::get('/program-planning/initiative-relation', [InitiativeRelationController::class, 'index'])->name('program-implementation.initiative-relation');
    Route::redirect('/program-planning/initiative', '/program-planning/initiative-relation');
    Route::get('/program-implementation', ProgramImplementationController::class)->name('program-implementation.index');
    
    Route::get('/program-implementation/budgeting', fn () => Inertia::render('Placeholder/Index', [
        'title' => 'Budgeting',
    ]))->name('program-implementation.budgeting');
    Route::get('/program-implementation/initiative-relation', [InitiativeRelationController::class, 'index'])->name('program-implementation.initiative-relation');
    Route::get('/architecture', fn () => Inertia::render('Placeholder/Index', [
        'title' => 'Architecture',
    ]))->name('architecture.index');
    Route::get('/master-data', MasterDataController::class)->name('master-data.index');
    Route::get('/program-information', fn () => Inertia::render('Placeholder/Index', [
        'title' => 'Program Information',
    ]))->name('program-information.index');
    Route::get('/strategic-pillars/{goal?}', [StrategicPillarController::class, 'index'])->name('strategic-pillars.index');

    // Digital Initiatives
    Route::resource('digital-initiatives', DigitalInitiativeController::class);
    // IT Initiatives & Charters
    // Roadmap — dedicated controller (all programs & per-program views)
    Route::get('/roadmap', [RoadmapController::class, 'index'])->name('roadmap.index');
    Route::get('/roadmap/edit', [RoadmapController::class, 'edit'])->name('roadmap.edit');
    Route::get('/roadmap/{program}', [RoadmapController::class, 'show'])->name('roadmap.show');
    Route::resource('it-initiatives', ITInitiativeController::class)
        ->parameters(['it-initiatives' => 'project']);
    Route::post('/it-initiatives/{project}/charter', [CharterController::class, 'store'])->name('it-initiatives.charter.store');
    Route::post('/it-initiatives/{project}/milestones', [MilestoneController::class, 'store'])->name('it-initiatives.milestones.store');
    Route::put('/it-initiatives/{project}/milestones/{milestone}', [MilestoneController::class, 'update'])->name('it-initiatives.milestones.update');
    Route::delete('/it-initiatives/{project}/milestones/{milestone}', [MilestoneController::class, 'destroy'])->name('it-initiatives.milestones.destroy');
    Route::post('/it-initiatives/{project}/implementation-status', [ITInitiativeController::class, 'storeImplementationStatus'])->name('it-initiatives.implementation-status.store');
    Route::put('/implementation-status/{id}', [ITInitiativeController::class, 'updateImplementationStatus'])->name('it-initiatives.implementation-status.update');
    Route::delete('/implementation-status/{id}', [ITInitiativeController::class, 'destroyImplementationStatus'])->name('it-initiatives.implementation-status.destroy');

    // Company Profile
    Route::resource('companies', CompanyController::class)->only(['index']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'approved', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/roles', [AdminRoleController::class, 'index'])->name('roles.index');
    Route::post('/roles', [AdminRoleController::class, 'store'])->name('roles.store');
    Route::post('/roles/permissions', [AdminRoleController::class, 'storePermission'])->name('roles.permissions.store');
    Route::put('/roles/{role}/permissions', [AdminRoleController::class, 'updatePermissions'])->name('roles.permissions.update');
    Route::delete('/roles/{role}', [AdminRoleController::class, 'destroy'])->name('roles.destroy');
});

/*
|--------------------------------------------------------------------------
| Catch-all redirect
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('login'));
