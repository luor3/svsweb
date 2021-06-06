<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\DynamicPagesController;

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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->prefix('admin/settings')->group(function () {
    Route::get('/', [SettingsController::class, 'show'])->name('settings');
    Route::get('/create', [SettingsController::class, 'showCreate'])->name('settings.create');
});






//------------------------------------------------------------------------------//
//-------------------------------- FIND DYNAMIC PAGES --------------------------//
//------------------------------------------------------------------------------//
Route::get('/{page}/', [DynamicPagesController::class, 'show']);
