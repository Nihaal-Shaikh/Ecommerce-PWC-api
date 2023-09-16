<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\SiteInfoController;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Get Visitor
Route::get('/getVisitor', [VisitorController::class, 'GetVisitorDetails']);

// Contact Page
Route::post('/postContact', [ContactController::class, 'PostContactDetails']);

// Site Info
Route::get('/allSiteInfo', [SiteInfoController::class, 'AllSiteInfo']);