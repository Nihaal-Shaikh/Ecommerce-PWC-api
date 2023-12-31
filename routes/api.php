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
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProductReviewController;
use App\Http\Controllers\Admin\ProductCartController;
use App\Http\Controllers\Admin\FavouritesController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\ForgotPasswordController;
use App\Http\Controllers\User\ResetPasswordController;
use App\Http\Controllers\User\UserController;

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

// Notification
Route::get('/notification', [NotificationController::class, 'NotificationHistory']);

// Search
Route::get('/search/{key}', [ProductListController::class, 'SearchByProduct']);

// Similar Products
Route::get('/similar/{subCategory}',[ProductListController::class, 'SimilarProducts']);

// Product Cart
Route::post('/addToCart',[ProductCartController::class, 'AddToCart']);

// Cart Count
Route::get('/cartCount/{productCode}',[ProductCartController::class, 'CartCount']);

// Favourites
Route::get('/favourite/{productCode}/{email}',[FavouritesController::class, 'AddFavourite']);

// Favourite List
Route::get('/favouriteList/{email}',[FavouritesController::class, 'FavouriteList']);

// Remove Favourite
Route::get('/removeFavourite/{productCode}/{email}',[FavouritesController::class, 'FavouriteRemove']);

// Cart List
Route::get('/cartList/{email}',[ProductCartController::class, 'CartList']);

// Remove from Cart
Route::get('/removeCartItem/{id}',[ProductCartController::class, 'RemoveCartItem']);

// Cart Quantity Plus
Route::get('/cartItemPlus/{id}/{quantity}/{price}',[ProductCartController::class, 'CartItemPlus']);

// Cart Quantity Minus
Route::get('/cartItemMinus/{id}/{quantity}/{price}',[ProductCartController::class, 'CartItemMinus']);

// Cart Order
Route::post('/cartOrder',[ProductCartController::class, 'CartOrder']);

// Order List
Route::get('/orderListByUser/{email}',[ProductCartController::class, 'OrderListByUser']);

// Product Review
Route::post('/postReview',[ProductReviewController::class, 'PostReview']);

// Product Review
Route::get('/productReview/{product_code}',[ProductReviewController::class, 'ProductReviewList']);

// Login
Route::post('/login', [AuthController::class, 'Login']);

// Register
Route::post('/register', [AuthController::class, 'Register']);

// Forgot Password 
Route::post('/forgotPassword',[ForgotPasswordController::class, 'ForgotPassword']);

// Reset Password Routes 
Route::post('/resetPassword',[ResetPasswordController::class, 'ResetPassword']);

// Current User
Route::get('/user',[UserController::class, 'User'])->middleware('auth:api');