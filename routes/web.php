<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\BookmarkController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PosterController;
use App\Http\Controllers\SearchController;

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

Auth::routes();

Route::get('/verify-email/{token}/{email}', [\App\Http\Controllers\Auth\VerificationController::class, 'verifyEmail'])->name('emailVerify');
Route::get('/', [PagesController::class, 'index'])->name('pages.index');
Route::get('/events', [PagesController::class, 'events'])->name('pages.events');
Route::get('/live_events/{id}', [PagesController::class, 'live_event'])->name('pages.live.show');
Route::get('/recurring_events/{id}', [PagesController::class, 'recurring_event'])->name('pages.recurring.show');

Route::get('/all_live_events/{id}', [PagesController::class, 'all_live_event'])->name('pages.live.showAll');
Route::get('/all_recurring_events/{id}', [PagesController::class, 'all_recurring_event'])->name('pages.recurring.showAll');

Route::get('/search-poster', [SearchController::class, 'searchPoster'])->name('pages.search');
Route::get('/sort-poster-by-category', [SearchController::class, 'sortByCategory'])->name('pages.sortCategory');
Route::get('/sort-poster-by-when', [SearchController::class, 'sortByWhen'])->name('pages.sortWhen');
Route::get('/sort-poster-by-recurring-frequency', [SearchController::class, 'sortByFrequency'])->name('pages.sortFrequency');


Route::middleware('auth')->group(function () {
    Route::get('/account', [PagesController::class, 'account'])->name('pages.account');
    Route::get('/change_password', [ChangePasswordController::class, 'index'])->name('password.change');
    Route::put('/change_password', [ChangePasswordController::class, 'change_password'])->name('password.change.store');

    Route::resource('/poster', PosterController::class);
    Route::get('/submission', [PosterController::class, 'submission'])->name('poster.submission');
    Route::get('/my-posted-events', [PagesController::class, 'myPostedEvents'])->name('pages.eventsPosted');
    Route::get('/dashboard', [PagesController::class, 'dashboard'])->name('pages.dashboard');
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('pages.bookmarks');
    Route::post('/add-bookmark/{eventId}', [BookmarkController::class, 'store'])->name('pages.bookmarks.add');
    Route::post('/remove-bookmark/{bookmarkId}', [BookmarkController::class, 'remove'])->name('pages.bookmarks.remove');


    Route::middleware('is_admin')->group(function () {
        Route::get('/category', [AdminController::class, 'category'])->name('admin.category');
        Route::post('/category', [AdminController::class, 'storeCategory'])->name('admin.category.store');
        Route::delete('/category/{id}', [AdminController::class, 'deleteCategory'])->name('admin.category.delete');
        Route::get('/approval', [AdminController::class, 'approval'])->name('admin.approval');
        Route::get('/approval/{id}', [AdminController::class, 'showApproval'])->name('admin.approval.show');
        Route::post('/approval/{id}', [AdminController::class, 'reviewPoster'])->name('admin.approval.review');
        Route::delete('/delete_poster/{id}', [AdminController::class, 'deletePoster'])->name('poster.delete');
        Route::put('/change-category/{id}', [AdminController::class, 'changeCategory'])->name('admin.changeCategory');
        // Route::get('/admins', [AdminController::class, 'getAdmins'])->name('admin.createAdmin.view');
        // Route::post('/add-admin', [AdminController::class, 'addAdmin'])->name('admin.createAdmin.create');
        // Route::delete('/remove-admin/{id}', [AdminController::class, 'removeAdmin'])->name('admin.createAdmin.remove');

        Route::middleware('is_super_admin')->group(function () {
            Route::get('/admins', [AdminController::class, 'getAdmins'])->name('admin.createAdmin.view');
            Route::post('/add-admin', [AdminController::class, 'addAdmin'])->name('admin.createAdmin.create');
            Route::delete('/remove-admin/{id}', [AdminController::class, 'removeAdmin'])->name('admin.createAdmin.remove');

            Route::get('/super-admins', [AdminController::class, 'getSuperAdmins'])->name('admin.createAdmin.viewSuperAdmin');
            Route::post('/add-super-admin', [AdminController::class, 'addSuperAdmin'])->name('admin.createAdmin.createSuperAdmin');
            Route::delete('/remove-super-admin/{id}', [AdminController::class, 'removeSuperAdmin'])->name('admin.createAdmin.removeSuperAdmin');
        });
    });
});
