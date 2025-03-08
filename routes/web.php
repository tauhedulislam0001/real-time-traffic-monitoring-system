<?php

use App\Http\Controllers\TrafficController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/traffic', [TrafficController::class, 'getTrafficData']);
