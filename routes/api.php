<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

Route::prefix('v1')->group(function(){

    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('users', UserController::class);
    
    // CUSTOM ROUTES
    Route::post('/login', [UserController::class, 'login']);

});