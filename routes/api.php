<?php

use App\Http\Controllers\Api\V1\BestSellersController;
use Illuminate\Support\Facades\Route;

Route::get('/v1/best-sellers', [BestSellersController::class, 'index'])
    ->middleware('auth:sanctum');
