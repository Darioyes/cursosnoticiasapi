<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UsersController;
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

//ruta de login
Route::post('users/login', [UsersController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::resource('users',UsersController::class )->except(['create','edit']);
    //ruta de tipo recurso para users
    //ruta de logout
    Route::post('users/logout', [UsersController::class, 'logout']);
    
});
