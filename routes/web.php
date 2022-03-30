<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\IssueController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SetupController;
use App\Http\Controllers\Admin\SprintController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->as('admin.')->middleware(['admin'])->group(function () {

        // only super admin middleware
        Route::middleware(['super-admin'])->group(function () {
            Route::get('/setup', [SetupController::class, 'index'])->name('setup');
            Route::resource('projects', ProjectController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
            Route::resource('users', UserController::class);
            Route::post('issues/sort', [IssueController::class, 'sort'])->name('issues.sort');
            Route::get('projects/{project:id}/charts', [ProjectController::class, 'chart'])->name('projects.charts');
            Route::resource('projects.sprints', SprintController::class)->scoped()->only(['destroy', 'edit', 'update']);
        });


        // for both admin/super admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [UserController::class, 'profile'])->name('profile.edit');
        Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
        Route::resource('projects', ProjectController::class)->only(['show', 'index']);
        Route::resource('projects.issues', IssueController::class)->scoped()->only(['index', 'show']);
        Route::resource('projects.sprints', SprintController::class)->scoped()->only(['index']);
    });
});



require __DIR__ . '/auth.php';
