<?php

namespace App\Http\Controllers\Users;

use App\Models\Users\News as newsFront;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

use App\Http\Responses\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Users\News;


class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            //traemos paginadas las noticias sin paginación
            $news = newsFront::with(['articles','category_news', 'category_course', 'comments'])
            ->orderBy('id', 'desc')
            ->get();
            //retornamos la respuesta
            return ApiResponse::success('Listado de noticias', Response::HTTP_OK, $news);

        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            //traemos la noticia por id
            //si es noticia o curso hacemos la relación con eloquent
            //!estamos creando un objeto de la clase News y llamando a la función with
            //!dentro de la función with le pasamos un array con las relaciones que queremos hacer
            //!en este caso category_news y category_course que son las funciones que tenemos en el modelo News
            $news = newsFront::with(['articles','category_news', 'category_course', 'comments'])
            ->where('id', $id)
            ->first();
            //retornamos la respuesta
            return ApiResponse::success('Noticia encontrada', Response::HTTP_OK, $news);
            }catch(ModelNotFoundException $e){
        }catch(ModelNotFoundException $e){
            //si no existe el id de la noticia retornamos un mensaje de error
            return ApiResponse::error('La noticia que busca no existe', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, NewsFront $newsFront)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(NewsFront $newsFront)
    // {
    //     //
    // }

    //obtener noticias por id de categoría
    public function getNewsByCategory($id)
    {
        try{
            //traemos las noticias por id de categoría
            $news = newsFront::with(['articles','category_news', 'category_course', 'comments'])
            ->where('category_news_id', $id)
            ->orderBy('id', 'desc')
            ->get();
            //retornamos la respuesta
            return ApiResponse::success('Listado de noticias por categoría', Response::HTTP_OK, $news);

        }catch(ModelNotFoundException $e){
            return ApiResponse::error('La categoría que busca no existe', Response::HTTP_INTERNAL_SERVER_ERROR);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

    //obtener noticias por el campo featured sea igual a 1 y el campo visible sea igual a 1
    public function getFeaturedNews()
    {
        try{
            //traemos las noticias c
            $news = newsFront::with(['articles','category_news', 'category_course', 'comments'])
            ->where('featured', 1)
            ->where('visible', 1)
            ->where('content', 'news')
            ->orderBy('id', 'desc')
            ->get();
            //retornamos la respuesta
            return ApiResponse::success('Listado de noticias destacadas', Response::HTTP_OK, $news);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('La categoría que busca no existe', Response::HTTP_INTERNAL_SERVER_ERROR);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //traer cursos pore por el campo featured sea igual a 1 y el campo visible sea igual a 1
    public function getFeaturedCourses()
    {
        try{
            //traemos las el curso
            $news = newsFront::with(['articles','category_news', 'category_course', 'comments'])
            ->where('featured', 1)
            ->where('visible', 1)
            ->where('content', 'course')
            ->orderBy('id', 'desc')
            ->get();
            //retornamos la respuesta
            return ApiResponse::success('Listado de noticias destacadas', Response::HTTP_OK, $news);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('La categoría que busca no existe', Response::HTTP_INTERNAL_SERVER_ERROR);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //obtener noticias por id de curso
    public function getNewsByCourse($id)
    {
        try{
            //traemos las noticias por id de curso
            $news = newsFront::with(['articles','category_news', 'category_course', 'comments'])
            ->where('category_course_id', $id)
            ->orderBy('id', 'desc')
            ->get();
            //retornamos la respuesta
            return ApiResponse::success('Listado de noticias por curso', Response::HTTP_OK, $news);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('La categoría que busca no existe', Response::HTTP_INTERNAL_SERVER_ERROR);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
