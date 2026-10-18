<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OsonaeController;
use App\Http\Controllers\ShozokuController; 
use App\Http\Controllers\EditTableController;
use App\Http\Controllers\KaigaiController;
use App\Http\Controllers\LoginDataController;
use App\Http\Controllers\CommonPasswordController;

Route::post(
    '/verify-common-password',
    [CommonPasswordController::class, 'verify']
)->middleware('throttle:5,1');

Route::post('/logindata/save', [LoginDataController::class, 'save']);
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
// ★追加
Route::get('/osonae/last-saved', [OsonaeController::class, 'lastSaved']);
Route::get(
    '/kaigai/last-saved',
    [KaigaiController::class, 'lastSaved']
);

Route::post('/login', [ShozokuController::class, 'login'])
    ->middleware('throttle:5,1');
