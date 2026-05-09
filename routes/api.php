<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OsonaeController;

Route::get('/osonae', [OsonaeController::class, 'index']);
