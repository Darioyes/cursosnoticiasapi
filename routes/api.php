<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\CategoriesNewsController;
use App\Http\Controllers\Admin\CategoriesCoursesController;
use App\Http\Controllers\Admin\ArticlesController;


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
    //ruta de tipo recurso para users
    Route::resource('users',UsersController::class )->except(['create','edit']);
    //ruta de logout de users
    Route::post('users/logout', [UsersController::class, 'logout']);
    //ruta de tipo recurso para news
    Route::resource('news',NewsController::class )->except(['create','edit']);
    //ruta de tipo recurso para categories_news
    Route::resource('categories_news',CategoriesNewsController::class )->except(['create','edit']);
    //ruta de tipo recurso para categories_courses
    Route::resource('categories_courses',CategoriesCoursesController::class )->except(['create','edit']);
    //ruta de tipo recurso para articles
    Route::resource('articles',ArticlesController::class )->except(['create','edit']);
});
