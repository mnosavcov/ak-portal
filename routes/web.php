<?php

use App\Http\Controllers\AdminController;
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
Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

Route::middleware('auth')->group(function () {
    Route::get('file/{project}/{project_file}/{hash}/{filename}', [ProjectController::class, 'file'])->name('file');
    Route::get('gallery/{project}/{project_gallery}/{hash}/{filename}', [ProjectController::class, 'gallery'])->name('gallery');

    Route::resource('projects', ProjectController::class)->except(['create', 'update', 'index', 'show']);
    Route::get('projects/create/{accountType}', [ProjectController::class, 'create'])->name('projects.create');
    Route::get('projects/prepare/{project}', [ProjectController::class, 'prepare'])->name('projects.prepare');
    Route::post('projects/confirm/{project}', [ProjectController::class, 'confirm'])->name('projects.confirm');
    // projects.update umoznuje pouze metodu PUT/PATCH ale nefunguje odesilani dat pres fetch()
    Route::post('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');

    Route::get('/nastaveni-uctu', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/nastaveni-uctu', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/nastaveni-uctu', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profil/investor', [ProfileController::class, 'investor'])->name('profile.investor');
    Route::get('/profil/advertiser', [ProfileController::class, 'advertiser'])->name('profile.advertiser');
    Route::get('/profil/real-estate-broker', [ProfileController::class, 'realEstateBroker'])->name('profile.real-estate-broker');
    Route::post('/profil/save', [ProfileController::class, 'profileSave'])->name('profile.save');
    Route::get('/profil/overview/{account?}', [ProfileController::class, 'overview'])->name('profile.overview');

    Route::middleware('user.superadmin')->group(function () {
        Route::name('admin.')->group(function () {
            Route::prefix('admin')->group(function () {
                Route::view('/', 'admin.index')->name('index');
                Route::get('projects', [AdminController::class, 'projects'])->name('projects');
                Route::get('projects/{project}', [AdminController::class, 'projectEdit'])->name('projects.edit');
                Route::post('projects/{project}', [AdminController::class, 'projectSave'])->name('projects.edit');
            });
        });
    });
});

require __DIR__ . '/auth.php';
