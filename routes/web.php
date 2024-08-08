<?php

use App\Http\Controllers\Admin\ErrorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\App\BackupController;
use App\Http\Controllers\App\HomepageController;
use App\Http\Controllers\App\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Models\Category;
use App\Models\FormContact;
use App\Models\Project;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

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

Route::get('projekty/add/{accountType?}', function () {
    return app()->call('App\Http\Controllers\App\ProjectController@create', ['accountType' => 'advertiser']);
})->name('projects.add');

Route::view('jak-to-funguje', 'app.jak-to-funguje')->name('jak-to-funguje');
//Route::view('o-nas', 'homepage', [
//    'projects' => [
//        'Nejnovější projekty' => [
//            'selected' => '1',
//            'titleCenter' => true,
//            'data' => [
//                '1' => Project::isPublicated()->forDetail()->get(),
//            ],
//        ]
//    ],
//])->name('o-nas');

Route::get('/', [HomepageController::class, 'index'])->name('homepage');
Route::get('sitemap.xml', [HomepageController::class, 'sitemap'])->name('sitemap');
Route::get('kontakt', [HomepageController::class, 'kontakt'])->name('kontakt');
Route::post('save-email', [HomepageController::class, 'saveEmail'])->name('save-email');
Route::post('ajax-form', function (Request $request) {
    $data = json_decode($request->post('data'), true);
    $subject = 'Nová zpráva z kontaktního formuláře na PED.cz';
    $polozky = [
        'pozadavek' => 'Dotaz',
        'kontaktJmeno' => 'Jméno',
        'kontaktPrijmeni' => 'Příjmení',
        'kontaktFirma' => 'Firma',
        'kontaktEmail' => 'E-mail',
        'kontaktTelefon' => 'Telefonní číslo',
    ];
    $form = new FormContact(['input_data' => serialize($data)]);

    $form->save();

    $contentMy = '';
    foreach ($polozky as $key => $item) {
        $value = ($data[$key] ?? null);
        if (empty($value)) {
            continue;
        }

        $contentMy .= '<strong>' . $item . ':</strong> ' . $value . "\n";
    }

    Mail::send([], [], function ($message) use ($subject, $contentMy, $data) {
        $message->to([env('MAIL_TO_INFO'), env('MAIL_TO_INFO2')])
            ->subject($subject)
            ->html(
                nl2br($contentMy),
                'text/html'
            );
    });

    return response()->json(['status' => 'success']);
})->name('ajax-form');
Route::get('gallery/{project}/{project_gallery}/{hash}/{filename}', [ProjectController::class, 'gallery'])->name('gallery');
Route::get('image/{project}/{project_image}/{hash}/{filename}', [ProjectController::class, 'image'])->name('image');
Route::get('zip/{project}/{hash}/{filename}', [ProjectController::class, 'zip'])->name('zip');
Route::view('zasady-zpracovani-osobnich-udaju', 'app.zasady-zpracovani-osobnich-udaju')->name('zasady-zpracovani-osobnich-udaju');
Route::get('vseobecne-obchodni-podminky', function () {
    return view('app.vseobecne-obchodni-podminky');
})->name('vseobecne-obchodni-podminky');

Route::middleware('auth')->group(function () {
    Route::get('file/{project}/{project_file}/{hash}/{filename}', [ProjectController::class, 'file'])->name('file');

    Route::resource('projekty', ProjectController::class)
        ->except(['create', 'update', 'index', 'show', 'edit'])
        ->parameters(['projekty' => 'project'])
        ->names([
//            'index' => 'projects.index',
//            'create' => 'projects.create',
            'store' => 'projects.store',
//            'show' => 'projects.show',
//            'edit' => 'projects.edit',
//            'update' => 'projects.update',
            'destroy' => 'projects.destroy',
        ]);
    Route::get('projekty/{project:page_url}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::get('projekty/create/select', [ProjectController::class, 'createSelect'])->name('projects.create.select');
    Route::get('projekty/create/{accountType}', [ProjectController::class, 'create'])->name('projects.create');
    Route::get('projekty/prepare/{project:page_url}', [ProjectController::class, 'prepare'])->name('projects.prepare');
    Route::post('projekty/confirm/{project}', [ProjectController::class, 'confirm'])->name('projects.confirm');
    Route::get('projekty/request-details/{project}', [ProjectController::class, 'requestDetails'])->name('projects.request-details');
    Route::post('projekty/set-public', [ProjectController::class, 'setPublic'])->name('projects.set-public');
    Route::post('projekty/add-offer', [ProjectController::class, 'addOffer'])->name('projects.add-offer');
    Route::post('projekty/pick-a-winner', [ProjectController::class, 'pickAWinner'])->name('projects.pick-a-winner');
    Route::post('projekty/store-temp-file/{uuid}', [ProjectController::class, 'storeTempFile'])->name('projects.store-temp-file');
    Route::post('projekty/get-vs/{project:page_url}', [ProjectController::class, 'paymentData'])->name('projects.get-vs');

    // projects.update umoznuje pouze metodu PUT/PATCH ale nefunguje odesilani dat pres fetch()
    Route::post('projekty/{project}', [ProjectController::class, 'update'])->name('projects.update');

    Route::get('/projekty/auction/check/max-bid-id/{project}', [ProjectController::class, 'mexBidId']);
    Route::get('/projekty/auction/read-actual-data/{project}', [ProjectController::class, 'readActualData']);

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
    Route::post('profil/hide-verify-info/{type}', [ProfileController::class, 'hideVerifyInfo'])->name('profile.hide-verify-info');
    Route::post('profil/set-account-types', [ProfileController::class, 'setAccountTypes'])->name('profile.set-account-types');

    Route::middleware('user.superadmin')->group(function () {
        Route::name('admin.')->group(function () {
            Route::prefix('admin')->group(function () {
                Route::get('/', [AdminController::class, 'index'])->name('index');

                Route::get('projekty', [AdminController::class, 'projects'])->name('projects');
                Route::post('projekty/{offer_id}/set-principal-paid', [AdminController::class, 'setPrincipalPaid'])->name('projects.set-principal-paid');
                Route::get('projekty/{project}', [AdminController::class, 'projectEdit'])->name('projects.edit');
                Route::post('projekty/{project}', [AdminController::class, 'projectSave'])->name('projects.save');
                Route::post('projects/store-temp-file/{uuid}', [AdminController::class, 'storeTempFile'])->name('projects.store-temp-file');

                Route::get('categories', [AdminController::class, 'categories'])->name('categories');
                Route::post('save-categories', [AdminController::class, 'saveCategories'])->name('save-categories');

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

                Route::get('payments', [AdminController::class, 'paymentsShow'])->name('payments.show');
                Route::get('payment/fio-check', function () {
                    (new PaymentService)->checkPrincipal();
                    return redirect()->route('admin.payments.show');
                })->name('payments.fio-check');

                Route::get('/error', [ErrorController::class, 'index'])->name('error.index');
                Route::get('/error/load/{filename}', [ErrorController::class, 'load'])->name('error.load');
                Route::delete('/error/archive', [ErrorController::class, 'archive'])->name('error.archive');
            });
        });
    });
});

Route::get('{category?}/{subcategory?}', [ProjectController::class, 'index'])
    ->where(
        'category',
        implode('|', array_column(Category::CATEGORIES, 'url'))
    )->name('projects.index.category');
Route::get('projekty', [ProjectController::class, 'index'])
    ->name('projects.index');

Route::get('/keep-session', function () {
    return response()->json(['status' => 'ok']);
});

require __DIR__ . '/auth.php';

if (Schema::hasTable('projects') && Project::count()) {
    Route::match(['get', 'post'],'{project:page_url}', [ProjectController::class, 'show'])
        ->where('project', Project::pluck('page_url')->implode('|'))
        ->name('projects.show');
}

Route::get('backup/dujslP5khfi3mmgGtigEiyTaqVqCyfsA', BackupController::class)->name('backup');
Route::get('payment/fio-check/tsGkskqWZcmVsZIYJAn7qUQb0Xowe7pF', function () {
    (new PaymentService)->checkPrincipal();
})->name('payment.fio-check');
