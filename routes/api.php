<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ANPRController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/anpr-detection', [ANPRController::class, 'handleDetection'])->name('api.anpr.detection');

