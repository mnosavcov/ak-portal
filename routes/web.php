<?php

use App\Http\Controllers\App\HomepageController;
use App\Http\Controllers\App\ProjectController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomepageController::class, 'index'])->name('homepage');
Route::resource('projects', ProjectController::class);

Route::middleware('auth')->group(function () {
    Route::get('/nastaveni-uctu', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/nastaveni-uctu', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/nastaveni-uctu', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profil/investor', [ProfileController::class, 'investor'])->name('profile.investor');
    Route::get('/profil/advertiser', [ProfileController::class, 'advertiser'])->name('profile.advertiser');
    Route::get('/profil/real-estate-broker', [ProfileController::class, 'realEstateBroker'])->name('profile.real-estate-broker');
    Route::post('/profil/save', [ProfileController::class, 'profileSave'])->name('profile.save');
    Route::get('/profil/overview', [ProfileController::class, 'overview'])->name('profile.overview');

    Route::prefix('admin')->group(function () {
        Route::view('/', 'admin.index');
    });
});

require __DIR__ . '/auth.php';
