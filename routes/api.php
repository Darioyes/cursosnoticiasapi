<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\CategoriesNewsController;
use App\Http\Controllers\Admin\CategoriesCoursesController;
use App\Http\Controllers\Admin\ArticlesController;
use App\Http\Controllers\Admin\CommentsController;
use App\Http\Controllers\Admin\ContactController;
//frontend
use App\Http\Controllers\Users\NewsController as NewsFrontController;
use App\Http\Controllers\Users\UsersController as UsersFrontController;
use App\Http\Controllers\Users\CommentsController as CommentsFrontController;
use App\Http\Controllers\Users\ContactController as ContactFrontController;
use App\Http\Controllers\Users\CategoriesCoursesController as CategoriesCoursesFrontController;
use App\Http\Controllers\Users\CategoriesNewsController as CategoriesNewsFrontController;


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


//!rutas backend
//ruta de login
Route::post('users/login', [UsersController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    //ruta de tipo recurso para users
    Route::resource('users',UsersController::class )->except(['create','edit']);
    //ruta de logout de users
    Route::post('users/logout', [UsersController::class, 'logout']);
    //ruta de tipo recurso para news
    Route::resource('news',NewsController::class )->except(['create','edit']);
     //ruta de articles para modificar
     Route::post('news2/{id}', [NewsController::class, 'update']);
    //ruta de tipo recurso para categories_news
    Route::resource('categories_news',CategoriesNewsController::class )->except(['create','edit']);
    //ruta de tipo recurso para categories_courses
    Route::resource('categories_courses',CategoriesCoursesController::class )->except(['create','edit']);
    //ruta de tipo recurso para articles
    Route::resource('articles',ArticlesController::class )->except(['create','edit']);
    //ruta de articles para modificar
    Route::post('articles2/{id}', [ArticlesController::class, 'update']);
    //ruta de tipo recurso de comments
    Route::resource('comments',CommentsController::class )->except(['create','edit']);
    //ruta de tipo recurso de contact
    Route::resource('contact',ContactController::class )->except(['create','edit']);
    //ruta para descargar archivo
    Route::get('contact/download/{file}', [ContactController::class, 'download']);
});

//!rutas frontend
//el metodo only nos permite definir que rutas queremos que se creen
Route::resource('noticursos', NewsFrontController::class)->only(['index', 'show']);
//ruta de noticias por categoria
Route::get('noticias/categoria/{id}', [NewsFrontController::class, 'getNewsByCategory']);
//ruta de noticias por curso
Route::get('noticias/curso/{id}', [NewsFrontController::class, 'getNewsByCourse']);
//ruta de noticias por el featured
Route::get('noticias-destacadas', [NewsFrontController::class, 'getFeaturedNews']);
//ruta de cursos destacados
Route::get('cursos-destacados', [NewsFrontController::class, 'getFeaturedCourses']);


//ruta comentarios
Route::resource('noticias/comentarios', CommentsFrontController::class)->only(['store']);

//ruta contacto
Route::resource('noticias/contactame', ContactFrontController::class)->only(['store']);

//ruta de login
Route::post('noticias/login', [UsersFrontController::class, 'login']);

//ruta de registro
Route::post('noticias/registro', [UsersFrontController::class, 'store']);
//ruta de verificación de email
Route::get('noticias/verificar/{id}/{hash}', [UsersFrontController::class, 'verify'])->name('verification.verify');

//ruta categorias cursos
Route::get('categorias-cursos', [CategoriesCoursesFrontController::class, 'index']);

//ruta de categorias de noticias
Route::get('categorias-noticias', [CategoriesNewsFrontController::class, 'index']);

//ruta para resetear password
//Route::post('noticias/reset-password', [UsersFrontController::class, 'resetPassword']);


//grupo de rutas de autenticación
Route::middleware(['auth:sanctum','verified'])->group(function(){
    //ruta de logout
    Route::post('noticias/logout', [UsersFrontController::class, 'logout']);

    //ruta usuarios
    Route::resource('noticias/usuario', UsersFrontController::class)->only(['show', 'update']);
});

