<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\DynamicPagesController;
use App\Http\Controllers\Frontend\InputGeneratorController;

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



/*Route::get('/', function () {
    return view('welcome');
}); */

Route::get('/', HomeController::class)->name('homepage');

Route::get('/input-generator', [InputGeneratorController::class, 'show'])->name("input-generator");

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->prefix('admin/settings')->group(function () {
    Route::get('/', [SettingsController::class, 'show'])->name('settings');
    Route::get('/create', [SettingsController::class, 'showCreate'])->name('settings.create');
});

Route::middleware(['auth:sanctum', 'verified'])->prefix('admin/pages')->group(function () {
    Route::get('/', [PagesController::class, 'show'])->name('pages');
    Route::get('/create', [PagesController::class, 'showCreate'])->name('pages.create');
});

Route::middleware(['auth:sanctum', 'verified'])->prefix('admin/users')->group(function () {
    Route::get('/', [UsersController::class, 'show'])->name('users');
    Route::get('/create', [UsersController::class, 'showCreate'])->name('users.create');
});



Route::post('/submit/{formType}', [SubmitController::class, 'submit']);



//------------------------------------------------------------------------------//
//-------------------------------- FIND DYNAMIC PAGES --------------------------//
//------------------------------------------------------------------------------//
Route::get('/{page}/', [DynamicPagesController::class, 'show']);
