<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OsonaeController;
use App\Http\Controllers\ShozokuController; 

Route::get('/osonae', [OsonaeController::class, 'index']);
Route::post('/osonae/save', [OsonaeController::class, 'save']);
Route::get('/shozoku', [ShozokuController::class, 'index']);
Route::post('/osonae/delete', [OsonaeController::class, 'delete']);
Route::post('/osonae/replaceAll', [OsonaeController::class, 'replaceAll']);
