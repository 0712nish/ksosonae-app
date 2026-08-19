<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OsonaeController;
use App\Http\Controllers\ShozokuController; 
use App\Http\Controllers\EditTableController;
use App\Http\Controllers\KaigaiController;

Route::get('/osonae', [OsonaeController::class, 'index']);
Route::post('/osonae/save', [OsonaeController::class, 'save']);
Route::get('/shozoku', [ShozokuController::class, 'index']);
Route::post('/osonae/delete', [OsonaeController::class, 'delete']);
Route::post('/osonae/replaceAll', [OsonaeController::class, 'replaceAll']);
Route::get('/kaigai', [KaigaiController::class, 'index']);
Route::post('/kaigai/save', [KaigaiController::class, 'save']);
Route::post('/kaigai/delete', [KaigaiController::class, 'delete']);
Route::get('/osonaerice', [OsonaeController::class, 'riceIndex']);
Route::post('/osonaerice/save',[OsonaeController::class, 'riceSave']);
Route::get('/editdate', [EditTableController::class, 'index']);
