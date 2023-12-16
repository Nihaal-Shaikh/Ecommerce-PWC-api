<?php

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
    Route::post('/store', [CategoryController::class, 'StoreCategory'])->name('category.store');
    Route::get('/edit/{id}', [CategoryController::class, 'EditCategory'])->name('category.edit');
    Route::post('/update', [CategoryController::class, 'UpdateCategory'])->name('category.update');
    Route::get('/delete/{id}', [CategoryController::class, 'DeleteCategory'])->name('category.delete');
});