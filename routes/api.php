<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthUser;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


    Route::controller(AuthUser::class)->middleware('web')->group(function () {
        Route::post('/registation', 'registation');
        Route::post('/login', 'login');
       
        
    Route::middleware('auth:api')->group(function () {
        Route::get('/dashboard', 'dashboard');
        Route::get('/logout', 'logout');
        Route::get('/searchName', 'searchName');
        Route::get('/search_data', 'search_data');
        Route::post('/get_data', 'get_data');
        // searchAllData?searchData
        

    });

   
});