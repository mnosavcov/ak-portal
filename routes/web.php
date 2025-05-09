<?php

use App\Http\Controllers\Admin\ErrorController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LocalizationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\App\BackupController;
use App\Http\Controllers\App\HomepageController;
use App\Http\Controllers\App\ProjectController;
use App\Http\Controllers\App\ProjectQuestionController;
use App\Http\Controllers\App\ProjectActualityController;
use App\Http\Controllers\Auth\Ext\BankIdController;
use App\Http\Controllers\Auth\Ext\RivaasController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;
use App\Models\Category;
use App\Models\FormContact;
use App\Models\Project;
use App\Models\User;
use App\Models\UserVerifyService;
use App\Services\PaymentService;
use App\Services\UsersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
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
Route::get('project-tags/image/{project}/{project_tag}/{hash}/{filename}', [ProjectController::class, 'tagImage'])->name('project-tags.image');
Route::get('gallery/{project}/{project_gallery}/{hash}/{filename}', [ProjectController::class, 'gallery'])->name('gallery');
Route::get('image/{project}/{project_image}/{hash}/{filename}', [ProjectController::class, 'image'])->name('image');
Route::get('zip/{project}/{hash}/{filename}', [ProjectController::class, 'zip'])->name('zip');
Route::view('zasady-zpracovani-osobnich-udaju',
    'app.long-text', [
        'include' => 'lang.' . app()->getLocale() . '.zasady-zpracovani-osobnich-udaju',
        'breadText' => __('Zásady zpracování osobních údajů'),
        'breadUrl' => '/zasady-zpracovani-osobnich-udaju',
    ]
)->name('zasady-zpracovani-osobnich-udaju');
Route::view('vseobecne-obchodni-podminky',
    'app.long-text', [
        'include' => 'lang.' . app()->getLocale() . '.vseobecne-obchodni-podminky',
        'breadText' => __('Všeobecné obchodní podmínky'),
        'breadUrl' => '/vseobecne-obchodni-podminky',
    ]
)->name('vseobecne-obchodni-podminky');

Route::middleware('auth')->group(function () {
    Route::get('file/{project}/{project_file}/{hash}/{filename}', [ProjectController::class, 'file'])->name('file');
    Route::get('file/{project}/question/{project_question}/{question_file}/{hash}/{filename}', [ProjectQuestionController::class, 'file'], null)->name('question-file');
    Route::get('file/{project}/actuality/{project_actuality}/{actuality_file}/{hash}/{filename}', [ProjectActualityController::class, 'file'], null)->name('actuality-file');

    Route::post('project-questions/store-temp-file/{uuid}', [ProjectController::class, 'storeTempFile'])->name('project-questions.store-temp-file');
    Route::resource('project-questions', ProjectQuestionController::class);
    Route::post('project-questions/set-max-question-id/{project}', [ProjectController::class, 'setMaxQuestionId'])->name('project-questions.set-max-question-id');

    Route::post('project-actualities/store-temp-file/{uuid}', [ProjectController::class, 'storeTempFile'])->name('project-actualities.store-temp-file');
    Route::resource('project-actualities', ProjectActualityController::class);
    Route::post('project-actualities/set-max-actuality-id/{project}', [ProjectController::class, 'setMaxActualityId'])->name('project-actualities.set-max-actuality-id');

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
    Route::post('projekty/set-track/{project:page_url}', [ProjectController::class, 'setTrack'])->name('projects.set-track');

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

    Route::middleware('user.translator')->group(function () {
        Route::name('admin.')->group(function () {
            Route::prefix('admin')->group(function () {
                Route::prefix('localization')->group(function () {
                    Route::name('localization.')->group(function () {
                        Route::get('/', [LocalizationController::class, 'index'])->name('index');
                        Route::get('/load/{lng}/{meta}', [LocalizationController::class, 'load'])->name('load');
                        Route::post('/save/{lng}/{sub}', [LocalizationController::class, 'save'])->name('save');
                        Route::post('/set/test/{bool}', [LocalizationController::class, 'setTest'])->name('set.test');
                        Route::post('/set/test-lng/{lng}', [LocalizationController::class, 'setTestLng'])->name('set.test-lng');
                        Route::post('/set/from-lng/{lng}', [LocalizationController::class, 'setFromLng'])->name('set.from-lng');

                        Route::get('email/preview/{lng}/{template}', [LocalizationController::class, 'preview'])->name('email.preview');
                        Route::get('email/send-test/{lng}/{template}', [LocalizationController::class, 'sendTest'])->name('email.send-test');

                        Route::get('/load-long/{lng}/{path}', [LocalizationController::class, 'loadLong'])->name('load-long');
                        Route::post('/save-long/{lng}/{path}', [LocalizationController::class, 'saveLong'])->name('save-long');
                        Route::post('email/send-template-test', [LocalizationController::class, 'sendTemplateTest'])->name('email.send-template-test');
                    });
                });
            });
        });
    });

    Route::middleware('user.superadmin')->group(function () {
        Route::name('admin.')->group(function () {
            Route::prefix('admin')->group(function () {
                Route::get('/', [AdminController::class, 'index'])->name('index');
                Route::get('set-user-all-notify/{user}', function (User $user) {
                    if (empty($user)) {
                        abort(404);
                    }
                    (new UsersService())->setNotifications($user);
                    echo 'ok...';
                });

                Route::get('projekty', [AdminController::class, 'projects'])->name('projects');
                Route::post('projekty/{offer_id}/set-principal-paid', [AdminController::class, 'setPrincipalPaid'])->name('projects.set-principal-paid');
                Route::get('projekty/{project}', [AdminController::class, 'projectEdit'])->name('projects.edit');
                Route::post('projekty/{project}', [AdminController::class, 'projectSave'])->name('projects.save');
                Route::post('projekty/store-temp-file/{uuid}', [ProjectController::class, 'storeTempFile'])->name('projects.store-temp-file');
                Route::post('project-tags/store-temp-file/{uuid}', [AdminController::class, 'storeTempFile'])->name('project-tags.store-temp-file');

                Route::get('faq', [FaqController::class, 'index'])->name('faq');
                Route::delete('faq/remove/{faq}', [FaqController::class, 'remove'])->name('faq-remove');
                Route::post('faq/update/{faq?}', [FaqController::class, 'update'])->name('faq-update');

                Route::get('categories', [AdminController::class, 'categories'])->name('categories');
                Route::post('save-categories', [AdminController::class, 'saveCategories'])->name('save-categories');

                Route::get('users', [AdminController::class, 'users'])->name('users');
                Route::post('save-users', [AdminController::class, 'usersSave'])->name('save-users');
                Route::post('users/append-ok', [AdminController::class, 'usersAppendOk'])->name('users-append-ok');
                Route::view('new-advisor', 'admin.register', [
                    'title' => 'Vytvořit advisora',
                    'url' => url('admin/user-new-advisor'),
                ])->name('new-advisor');
                Route::view('new-admin', 'admin.register', [
                    'title' => 'Vytvořit administrátora',
                    'url' => url('admin/user-new-admin'),
                ])->name('new-admin');
                Route::view('new-translator', 'admin.register', [
                    'title' => 'Vytvořit překladatele',
                    'url' => url('admin/user-new-translator'),
                ])->name('new-translator');

                Route::view('advisor-ok', 'admin.register-ok', [
                    'title' => 'Účet advisora',
                    'url' => url('admin/new-advisor'),
                ])->name('advisor-ok');
                Route::view('admin-ok', 'admin.register-ok', [
                    'title' => 'Účet administrátora',
                    'url' => url('admin/new-admin'),
                ])->name('admin-ok');
                Route::view('translator-ok', 'admin.register-ok', [
                    'title' => 'Účet překladatele',
                    'url' => url('admin/new-translator'),
                ])->name('translator-ok');

                Route::post('user-new-advisor', [AdminController::class, 'addAdvisor'])->name('user.new-advisor');
                Route::post('user-new-admin', [AdminController::class, 'addAdmin'])->name('user.new-admin');
                Route::post('user-new-translator', [AdminController::class, 'addTranslator'])->name('user.new-translator');

                Route::post('project-question/confirm/{project_question}', [AdminController::class, 'adminQuestionConfirm'])->name('project-question.confirm');
                Route::post('project-question/update/{project_question}', [AdminController::class, 'adminQuestionUpdate'])->name('project-question.update');
                Route::post('project-actuality/confirm/{project_actuality}', [AdminController::class, 'adminActualityConfirm'])->name('project-actuality.confirm');
                Route::post('project-actuality/update/{project_actuality}', [AdminController::class, 'adminActualityUpdate'])->name('project-actuality.update');

                Route::get('payments', [AdminController::class, 'paymentsShow'])->name('payments.show');
                Route::get('payment/fio-check', function () {
                    (new PaymentService)->checkPrincipal();
                    return redirect()->route('admin.payments.show');
                })->name('payments.fio-check');

                Route::get('/error', [ErrorController::class, 'index'])->name('error.index');
                Route::get('/error/load/{filename}', [ErrorController::class, 'load'])->name('error.load');
                Route::delete('/error/archive', [ErrorController::class, 'archive'])->name('error.archive');;
            });
        });
    });
});

Route::get('{category?}/{subcategory?}', [ProjectController::class, 'index'])
    ->where(
        'category',
        implode('|', array_column(Category::getCATEGORIES(), 'url'))
    )->name('projects.index.category');
Route::get('projekty', [ProjectController::class, 'index'])
    ->name('projects.index');

Route::get('/keep-session', function () {
    return response()->json(['status' => 'ok']);
});

require __DIR__ . '/auth.php';

if (Schema::hasTable('projects') && Project::count()) {
    Route::match(['get', 'post'], '{project:page_url}', [ProjectController::class, 'show'])
        ->where('project', Project::pluck('page_url')->implode('|'))
        ->name('projects.show');
}

Route::prefix('auth/ext')->name('auth.ext.')->group(function () {
    Route::prefix('bankid')->name('bankid.')->group(function () {
        Route::get('verify-begin', [BankIdController::class, 'verifyBegin'])->name('verify-begin');
        Route::post('profile', [BankIdController::class, 'profile'])->name('profile');
        Route::post('notify', [BankIdController::class, 'notify'])->name('notify');

        // vytvareni funkcnosti pro notifikace (tohle je funkcnost )
        Route::prefix('localhost')->name('localhost.')->group(function () {
            // tohle zavola bankid
            Route::post('notify', [BankIdController::class, 'localhostNotifySet'])->name('notify.set');
            // tohle vraci server zpet na lokal
            Route::get('notify', [BankIdController::class, 'localhostNotifyGet'])->name('notify.get');
            // tohle volam ja z lokalu a vola to "notify"
            Route::get('update-data', [BankIdController::class, 'localhostNotifyUpdateData'])->name('notify.update-data');
        });
    });

    Route::prefix('rivaas')->name('rivaas.')->group(function () {
        Route::get('verify-begin', [RivaasController::class, 'verifyBegin'])->name('verify-begin');
        Route::get('verify-begin-from-local/{userid}/{redirect}', [RivaasController::class, 'verifyBegin'])->name('verify-begin-from-local');
        Route::post('callback', [RivaasController::class, 'callback'])->name('callback');
        Route::get('verified', [RivaasController::class, 'verified'])->name('verified');
        Route::get('rejected', [RivaasController::class, 'rejected'])->name('rejected');
        Route::get('unverified', [RivaasController::class, 'unverified'])->name('unverified');
        Route::get('logo/pvtrusted.svg', [RivaasController::class, 'logo'])->name('logo');

        Route::prefix('localhost')->name('localhost.')->group(function () {
            Route::get('verified/{data}/{userData}', [RivaasController::class, 'verifiedLocal'])->name('verified');
        });

        Route::get('test/{id}', function ($id) {
            dd(Cache::get('rivaas'), json_decode(Crypt::decryptString(UserVerifyService::find($id)->data)));
        });
    });
});

// crons
Route::get('backup/dujslP5khfi3mmgGtigEiyTaqVqCyfsA', BackupController::class)->name('backup');
Route::get('send-mail/SpACoO3DPrLH0a3vs20t1o7zqbHyYTPw', [EmailController::class, 'sendFromQueue'])->name('send-mail-queue');
Route::get('payment/fio-check/tsGkskqWZcmVsZIYJAn7qUQb0Xowe7pF', function () {
    (new PaymentService)->checkPrincipal();
})->name('payment.fio-check');

Route::prefix('test')->name('test.')->group(function () {
    Route::get('bankid-verify/{id?}', [TestController::class, 'testBankidVerify'])->name('bankid-verify');
    Route::get('user-backup/{id}', [TestController::class, 'testUserBackup'])->name('user-backup');
});
