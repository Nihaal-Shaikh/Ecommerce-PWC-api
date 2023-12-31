<?php

use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\HomeSliderController;
use App\Http\Controllers\Admin\ProductCartController;
use App\Http\Controllers\Admin\ProductListController;
use App\Http\Controllers\Admin\ProductReviewController;
use App\Http\Controllers\Admin\SiteInfoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CategoryController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.index');
    })->name('dashboard');
});

// Admin Logout
Route::get('/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');

Route::prefix('admin')->group(function () {

    Route::get('/user/profile', [AdminController::class, 'UserProfile'])->name('user.profile');
    Route::post('/user/profile/store', [AdminController::class, 'UserProfileStore'])->name('user.profile.store');
    Route::get('/change/password', [AdminController::class, 'ChangePassword'])->name('change.password');
    Route::post('/change/password/update', [AdminController::class, 'ChangePasswordUpdate'])->name('change.password.update');
});

Route::prefix('category')->group(function () {

    Route::get('/all', [CategoryController::class, 'AllCategories'])->name('all.categories');
    Route::get('/add', [CategoryController::class, 'AddCategory'])->name('add.category');
    Route::post('/store', [CategoryController::class, 'StoreCategory'])->name('category.store');
    Route::get('/edit/{id}', [CategoryController::class, 'EditCategory'])->name('category.edit');
    Route::post('/update', [CategoryController::class, 'UpdateCategory'])->name('category.update');
    Route::get('/delete/{id}', [CategoryController::class, 'DeleteCategory'])->name('category.delete');
});

Route::prefix('subcategory')->group(function () {

    Route::get('/all', [CategoryController::class, 'AllSubCategories'])->name('all.subcategories');
    Route::get('/add', [CategoryController::class, 'AddSubCategory'])->name('add.subcategory');
    Route::post('/store', [CategoryController::class, 'StoreSubCategory'])->name('subcategory.store');
    Route::get('/edit/{id}', [CategoryController::class, 'EditSubCategory'])->name('subcategory.edit');
    Route::post('/update', [CategoryController::class, 'UpdateSubCategory'])->name('subcategory.update');
    Route::get('/delete/{id}', [CategoryController::class, 'DeleteSubCategory'])->name('subcategory.delete');
});

Route::prefix('slider')->group(function () {

    Route::get('/all', [HomeSliderController::class, 'GetAllSlider'])->name('all.slider');
    Route::get('/add', [HomeSliderController::class, 'AddSlider'])->name('add.slider');
    Route::post('/store', [HomeSliderController::class, 'StoreSlider'])->name('slider.store');
    Route::get('/edit/{id}', [HomeSliderController::class, 'EditSlider'])->name('slider.edit');
    Route::post('/update', [HomeSliderController::class, 'UpdateSlider'])->name('slider.update');
    Route::get('/delete/{id}', [HomeSliderController::class, 'DeleteSlider'])->name('slider.delete');
});

Route::prefix('product')->group(function () {

    Route::get('/all', [ProductListController::class, 'GetAllProducts'])->name('all.products');
    Route::get('/add', [ProductListController::class, 'AddProduct'])->name('add.product');
    Route::post('/store', [ProductListController::class, 'StoreProduct'])->name('product.store');
    Route::get('/edit/{id}', [ProductListController::class, 'EditProduct'])->name('product.edit');
    Route::post('/update', [ProductListController::class, 'UpdateProduct'])->name('product.update');
    Route::get('/delete/{id}', [HomeSliderController::class, 'DeleteSlider'])->name('slider.delete');
});

// Contact Message

Route::get('/all/message', [ContactController::class, 'GetAllMessage'])->name('contact.message');
Route::get('/message/delete/{id}', [ContactController::class, 'DeleteMessage'])->name('message.delete');

// Product Review

Route::get('/all/reviews', [ProductReviewController::class, 'GetAllReviews'])->name('all.reviews');
Route::get('/review/delete/{id}', [ProductReviewController::class, 'DeleteReview'])->name('review.delete');

// Site Info
Route::get('/getsite/info', [SiteInfoController::class, 'GetSiteInfo'])->name('getsite.info');
Route::post('/update/siteinfo', [SiteInfoController::class, 'UpdateSiteInfo'])->name('update.siteinfo');

//
Route::prefix('order')->group(function () {

    Route::get('/pending', [ProductCartController::class, 'PendingOrders'])->name('pending.orders');
    Route::get('/processing', [ProductCartController::class, 'ProcessingOrders'])->name('processing.orders');
    Route::get('/completed', [ProductCartController::class, 'CompletedOrders'])->name('completed.orders');
    Route::get('/details/{id}', [ProductCartController::class, 'OrderDetails'])->name('order.details');
    Route::get('/status/processing/{id}', [ProductCartController::class, 'PendingToProcess'])->name('pending.process');
    Route::get('/status/complete/{id}', [ProductCartController::class, 'ProcessingToComplete'])->name('process.complete');
    Route::get('/delete/{id}', [ProductCartController::class, 'OrderDelete'])->name('order.delete');
});