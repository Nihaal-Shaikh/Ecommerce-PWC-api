<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\SiteInfoController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductListController;
use App\Http\Controllers\Admin\HomeSliderController;
use App\Http\Controllers\Admin\ProductDetailsController;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Get Visitor
Route::get('/getVisitor', [VisitorController::class, 'GetVisitorDetails']);

// Contact Page
Route::post('/postContact', [ContactController::class, 'PostContactDetails']);

// Site Info
Route::get('/allSiteInfo', [SiteInfoController::class, 'AllSiteInfo']);

// All Category
Route::get('/allCategory', [CategoryController::class, 'AllCategory']);

// Product List by Remark
Route::get('/productListByRemark/{remark}', [ProductListController::class, 'ProductListByRemark']);

// Product List by Category
Route::get('/productListByCategory/{category}', [ProductListController::class, 'ProductListByCategory']);

// Product List by Sub Category
Route::get('/productListBySubCategory/{category}/{subcategory}', [ProductListController::class, 'ProductListBySubCategory']);

// Home Slider
Route::get('/allSlider', [HomeSliderController::class, 'AllSlider']);

// Product Details
Route::get('/productDetails/{id}', [ProductDetailsController::class, 'ProductDetails']);