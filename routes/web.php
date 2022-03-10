<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DemosController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\SshserversController;
use App\Http\Controllers\SolverController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\GetstartedController;
use App\Http\Controllers\Frontend\DynamicPagesController;
use App\Http\Controllers\Frontend\InputGeneratorController;
use App\Http\Controllers\Frontend\XmlGeneratorController;
use App\Http\Controllers\Frontend\VtkController;
use App\Http\Controllers\Frontend\UsersProfileController;
use App\Http\Controllers\Frontend\JobsController;
use App\Http\Controllers\Frontend\AboutController;
use App\Models\User;


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


Route::get('/', HomeController::class)->name('homepage');

Route::get('/demos', GetstartedController::class)->name('demopage');

Route::get('/input-generator', InputGeneratorController::class)->name('input-generator');

Route::get('/xml-input-generator', XmlGeneratorController::class)->name('xml-input-generator');

Route::get('/vtk-visualizer', VtkController::class)->name('vtk-visualizer');

Route::get('/about', [AboutController::class,'show'])->name('aboutpage'); 

Route::get('/about/{name}/{id}', [AboutController::class,'getBio'])->name('biopage');

Route::middleware(['auth', 'verified', 'user.status.check'])->get('/user-profile', UsersProfileController::class)->name('userprofile');


Route::middleware(['auth:sanctum', 'verified', 'rolecheck:admin'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified', 'rolecheck:admin'])->prefix('admin/settings')->group(function () {
    Route::get('/', [SettingsController::class, 'show'])->name('settings');
    Route::get('/create', [SettingsController::class, 'showCreate'])->name('settings.create');
});

Route::middleware(['auth:sanctum', 'verified', 'rolecheck:admin'])->prefix('admin/pages')->group(function () {
    Route::get('/', [PagesController::class, 'show'])->name('pages');
    Route::get('/create', [PagesController::class, 'showCreate'])->name('pages.create');
});

Route::middleware(['auth:sanctum', 'verified', 'rolecheck:admin'])->prefix('admin/users')->group(function () {
    Route::get('/', [UsersController::class, 'show'])->name('users');
});

Route::middleware(['auth:sanctum', 'verified', 'rolecheck:admin'])->prefix('admin/demos')->group(function () {
    Route::get('/', [DemosController::class, 'show'])->name('demos');
    Route::get('/create', [DemosController::class, 'showCreate'])->name('demos.create');
});

Route::middleware(['auth:sanctum', 'verified', 'rolecheck:admin'])->prefix('admin/categories')->group(function () {
    Route::get('/', [CategoriesController::class, 'show'])->name('categories');
    Route::get('/create', [CategoriesController::class, 'showCreate'])->name('categories.create');
});

Route::middleware(['auth:sanctum', 'verified', 'rolecheck:admin'])->prefix('admin/jobs')->group(function () {
    Route::get('/', [App\Http\Controllers\JobsController::class, 'show'])->name('jobs.all');
});

Route::middleware(['auth:sanctum', 'verified', 'rolecheck:admin'])->prefix('admin/sshservers')->group(function () {
    Route::get('/', [SshserversController::class, 'show'])->name('sshservers');
    Route::get('/create', [SshserversController::class, 'showCreate'])->name('sshservers.create');
});

Route::middleware(['auth:sanctum', 'verified', 'rolecheck:admin'])->prefix('admin/solvers')->group(function () {
    Route::get('/', [SolverController::class, 'show'])->name('solvers');
    Route::get('/create', [SolverController::class, 'showCreate'])->name('solvers.create');
});

Route::middleware(['auth:sanctum', 'verified','user.status.check'])->prefix('jobs')->group(function () {
    Route::get('/', [JobsController::class, 'show'])->name('jobs');
    Route::get('/create', [JobsController::class, 'showCreate'])->name('jobs.create');
});


//------------------------------------------------------------------------------//
//-------------------------------- FIND DYNAMIC PAGES --------------------------//
//------------------------------------------------------------------------------//
Route::get('/{page}/', [DynamicPagesController::class, 'show']);

