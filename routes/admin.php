<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\WeeklyPlannerController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\CardNumberController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CourseContentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\PreviousYearExamController;
use App\Http\Controllers\Admin\QuestionBankController;
use App\Http\Controllers\Admin\WorksheetController;
use App\Http\Controllers\Admin\EducationalNoteController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\HeroSectionController;
use App\Http\Controllers\Admin\AgencyController;
use App\Http\Controllers\Admin\AboutSectionController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\AgencyServiceController;
use App\Http\Controllers\Admin\AgencyMediaController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ConductDocumentController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherClassController;
use App\Http\Controllers\Admin\TeacherController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Permission\Models\Permission;

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {

    Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {

        // ── Dashboard ─────────────────────────────────────────────────
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('logout', [LoginController::class, 'logout'])->name('admin.logout');

        // ── Admin profile ─────────────────────────────────────────────
        Route::get('/admin/edit/{id}',    [LoginController::class, 'editlogin'])->name('admin.login.edit');
        Route::post('/admin/update/{id}', [LoginController::class, 'updatelogin'])->name('admin.login.update');

        // ── Roles & Employees ─────────────────────────────────────────
        Route::resource('employee', EmployeeController::class, ['as' => 'admin'])->except(['show']);
        Route::get('role',               [RoleController::class, 'index'])->name('admin.role.index');
        Route::get('role/create',        [RoleController::class, 'create'])->name('admin.role.create');
        Route::get('role/{id}/edit',     [RoleController::class, 'edit'])->name('admin.role.edit');
        Route::patch('role/{id}',        [RoleController::class, 'update'])->name('admin.role.update');
        Route::post('role',              [RoleController::class, 'store'])->name('admin.role.store');
        Route::post('admin/role/delete',  [RoleController::class, 'delete'])->name('admin.role.delete');
        Route::delete('role/{id}',        [RoleController::class, 'destroy'])->name('admin.role.destroy');

        Route::get('/permissions/{guard_name}', function ($guard_name) {
            return response()->json(Permission::where('guard_name', $guard_name)->get());
        });

        // ── Portfolio Content ─────────────────────────────────────────────
        // Site Settings
        Route::get('site-settings',                [SiteSettingController::class, 'index'])->name('admin.site-settings.index');
        Route::put('site-settings',                [SiteSettingController::class, 'update'])->name('admin.site-settings.update');
        Route::post('site-settings/images',        [SiteSettingController::class, 'uploadImages'])->name('admin.site-settings.upload-images');

        // Hero Section
        Route::get('hero/edit',           [HeroSectionController::class, 'edit'])->name('admin.hero.edit');
        Route::put('hero',                [HeroSectionController::class, 'update'])->name('admin.hero.update');

        // About Section
        Route::get('about/edit',          [AboutSectionController::class, 'edit'])->name('admin.about.edit');
        Route::put('about',               [AboutSectionController::class, 'update'])->name('admin.about.update');

        // Agencies
        Route::get('agencies',                    [AgencyController::class, 'index'])->name('admin.agencies.index');
        Route::get('agencies/create',             [AgencyController::class, 'create'])->name('admin.agencies.create');
        Route::post('agencies',                   [AgencyController::class, 'store'])->name('admin.agencies.store');
        Route::get('agencies/{agency}/edit',      [AgencyController::class, 'edit'])->name('admin.agencies.edit');
        Route::put('agencies/{agency}',           [AgencyController::class, 'update'])->name('admin.agencies.update');
        Route::delete('agencies/{agency}',        [AgencyController::class, 'destroy'])->name('admin.agencies.destroy');

        // Agency Services
        Route::get('agencies/{agency}/services',                       [AgencyServiceController::class, 'index'])->name('admin.agencies.services.index');
        Route::get('agencies/{agency}/services/create',                [AgencyServiceController::class, 'create'])->name('admin.agencies.services.create');
        Route::post('agencies/{agency}/services',                      [AgencyServiceController::class, 'store'])->name('admin.agencies.services.store');
        Route::get('agencies/{agency}/services/{service}/edit',        [AgencyServiceController::class, 'edit'])->name('admin.agencies.services.edit');
        Route::put('agencies/{agency}/services/{service}',             [AgencyServiceController::class, 'update'])->name('admin.agencies.services.update');
        Route::delete('agencies/{agency}/services/{service}',          [AgencyServiceController::class, 'destroy'])->name('admin.agencies.services.destroy');

        // Agency Media
        Route::get('agencies/{agency}/media',                          [AgencyMediaController::class, 'index'])->name('admin.agencies.media.index');
        Route::get('agencies/{agency}/media/create',                   [AgencyMediaController::class, 'create'])->name('admin.agencies.media.create');
        Route::post('agencies/{agency}/media',                         [AgencyMediaController::class, 'store'])->name('admin.agencies.media.store');
        Route::get('agencies/{agency}/media/{medium}/edit',            [AgencyMediaController::class, 'edit'])->name('admin.agencies.media.edit');
        Route::put('agencies/{agency}/media/{medium}',                 [AgencyMediaController::class, 'update'])->name('admin.agencies.media.update');
        Route::delete('agencies/{agency}/media/{medium}',              [AgencyMediaController::class, 'destroy'])->name('admin.agencies.media.destroy');

        // Clients
        Route::get('clients',                  [ClientController::class, 'index'])->name('admin.clients.index');
        Route::get('clients/create',           [ClientController::class, 'create'])->name('admin.clients.create');
        Route::post('clients',                 [ClientController::class, 'store'])->name('admin.clients.store');
        Route::get('clients/{client}/edit',    [ClientController::class, 'edit'])->name('admin.clients.edit');
        Route::put('clients/{client}',         [ClientController::class, 'update'])->name('admin.clients.update');
        Route::delete('clients/{client}',      [ClientController::class, 'destroy'])->name('admin.clients.destroy');

    });

       
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'guest:admin'], function () {
    Route::get('login',  [LoginController::class, 'show_login_view'])->name('admin.showlogin');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');
});
