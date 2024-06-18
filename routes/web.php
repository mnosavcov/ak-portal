<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\App\HomepageController;
use App\Http\Controllers\App\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

Route::get('projects/add/{accountType?}', function () {
    return app()->call('App\Http\Controllers\App\ProjectController@create', ['accountType' => 'advertiser']);
})->name('projects.add');
Route::post('projects/save', [ProjectController::class, 'store'])->name('projects.save');

Route::view('jak-to-funguje', 'homepage', [
    'projects' => [
        'Nejnovější projekty' => [
            'selected' => '1',
            'titleCenter' => true,
            'data' => [
                '1' => Project::isPublicated()->forDetail()->get(),
            ],
        ]
    ],
])->name('jak-to-funguje');
Route::view('o-nas', 'homepage', [
    'projects' => [
        'Nejnovější projekty' => [
            'selected' => '1',
            'titleCenter' => true,
            'data' => [
                '1' => Project::isPublicated()->forDetail()->get(),
            ],
        ]
    ],
])->name('o-nas');
Route::view('kontakt', 'homepage', [
    'projects' => [
        'Nejnovější projekty' => [
            'selected' => '1',
            'titleCenter' => true,
            'data' => [
                '1' => Project::isPublicated()->forDetail()->get(),
            ],
        ]
    ],
])->name('kontakt');

Route::get('/', [HomepageController::class, 'index'])->name('homepage');
Route::post('save-email', [HomepageController::class, 'saveEmail'])->name('save-email');
Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('gallery/{project}/{project_gallery}/{hash}/{filename}', [ProjectController::class, 'gallery'])->name('gallery');
Route::get('zpracovani-osobnich-udaju', function () {
    $date = Carbon::create(env('DATE_PUBLISH'));
    $currentDateTime = clone $date;
    $currentDateTime->subHours(+2);

    if (!$currentDateTime->isPast()) {
        return view('app.zpracovani-osobnich-udaju-temp');
    }

    return view('app.zpracovani-osobnich-udaju');
})->name('zpracovani-osobnich-udaju');

Route::middleware('auth')->group(function () {
    Route::get('file/{project}/{project_file}/{hash}/{filename}', [ProjectController::class, 'file'])->name('file');

    Route::resource('projects', ProjectController::class)->except(['create', 'update', 'index', 'show']);
    Route::get('projects/create/{accountType}', [ProjectController::class, 'create'])->name('projects.create');
    Route::get('projects/prepare/{project}', [ProjectController::class, 'prepare'])->name('projects.prepare');
    Route::post('projects/confirm/{project}', [ProjectController::class, 'confirm'])->name('projects.confirm');
    Route::post('projects/add-offer', [ProjectController::class, 'addOffer'])->name('projects.add-offer');
    Route::post('projects/pick-a-winner', [ProjectController::class, 'pickAWinner'])->name('projects.pick-a-winner');
    // projects.update umoznuje pouze metodu PUT/PATCH ale nefunguje odesilani dat pres fetch()
    Route::post('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');

    Route::get('/nastaveni-uctu', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/nastaveni-uctu-verify', [ProfileController::class, 'editVerify'])->name('profile.edit-verify');
    Route::patch('/nastaveni-uctu', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/nastaveni-uctu', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profil/investor', [ProfileController::class, 'investor'])->name('profile.investor');
    Route::get('/profil/advertiser', [ProfileController::class, 'advertiser'])->name('profile.advertiser');
    Route::get('/profil/real-estate-broker', [ProfileController::class, 'realEstateBroker'])->name('profile.real-estate-broker');
    Route::post('/profil/save', [ProfileController::class, 'profileSave'])->name('profile.save');
    Route::get('/profil/overview/{account?}', [ProfileController::class, 'overview'])->name('profile.overview');
    Route::post('/profil/resend-verify-email', [ProfileController::class, 'resendValidationEmail'])
        ->middleware(['throttle:1,5,profile-resend-verify-email'])
        ->name('profile.resend-verify-email');

    Route::post('/profil/verify-new-email', [ProfileController::class, 'verifyNewEmail'])
        ->middleware(['throttle:1,5,profile-verify-new-email'])
        ->name('profile.verify-new-email');

    Route::post('/profil/verify-account', [ProfileController::class, 'verifyAccount'])->name('profile.verify-account');
    Route::post('profil/hide-verify-info', [ProfileController::class, 'hideVerifyInfo'])->name('profile.hide-verify-info');
    Route::post('profil/set-account-types', [ProfileController::class, 'setAccountTypes'])->name('profile.set-account-types');

    Route::middleware('user.superadmin')->group(function () {
        Route::name('admin.')->group(function () {
            Route::prefix('admin')->group(function () {
                Route::get('/', [AdminController::class, 'index'])->name('index');
                Route::get('projects', [AdminController::class, 'projects'])->name('projects');
                Route::post('projects/{offer_id}/set-principal-paid', [AdminController::class, 'setPrincipalPaid'])->name('projects.set-principal-paid');
                Route::get('projects/{project}', [AdminController::class, 'projectEdit'])->name('projects.edit');
                Route::post('projects/{project}', [AdminController::class, 'projectSave'])->name('projects.edit');
                Route::get('users', [AdminController::class, 'users'])->name('users');
                Route::post('save-users', [AdminController::class, 'usersSave'])->name('save-users');
                Route::view('new-advisor', 'admin.register', [
                    'title' => 'Vytvořit advisora',
                    'url' => url('admin/user-new-advisor'),
                ])->name('new-advisor');
                Route::view('new-admin', 'admin.register', [
                    'title' => 'Vytvořit administrátora',
                    'url' => url('admin/user-new-admin'),
                ])->name('new-admin');

                Route::view('advisor-ok', 'admin.register-ok', [
                    'title' => 'Účet advisora',
                    'url' => url('admin/new-advisor'),
                ])->name('advisor-ok');
                Route::view('admin-ok', 'admin.register-ok', [
                    'title' => 'Účet administrátora',
                    'url' => url('admin/new-admin'),
                ])->name('admin-ok');

                Route::post('user-new-advisor', [AdminController::class, 'addAdvisor'])->name('user.new-advisor');
                Route::post('user-new-admin', [AdminController::class, 'addAdmin'])->name('user.new-admin');
            });
        });
    });
});

require __DIR__ . '/auth.php';
