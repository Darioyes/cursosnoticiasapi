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
            //si es noticia o curso hacemos la relación con eloquent
            //!estamos creando un objeto de la clase News y llamando a la función with
            //!dentro de la función with le pasamos un array con las relaciones que queremos hacer
            //!en este caso category_news y category_course que son las funciones que tenemos en el modelo News
            $news = newsFront::with(['articlesFront','categoryNewsFront', 'categoryCourseFront','commentsFront'])
                        ->findOrFail($id)
                        ->makeHidden(['category_news_id', 'category_course_id']);
            //retornamos la respuesta
            return ApiResponse::success('Detalle de noticia', Response::HTTP_OK, $news);
            //buscamos la noticia por id
            // $news = News::findOrFail($id);
            // //retornamos la respuesta
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
}
