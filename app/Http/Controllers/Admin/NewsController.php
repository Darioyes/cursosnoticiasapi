<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

use App\Models\Admin\News;
use App\Http\Responses\ApiResponse;
use App\Http\Requests\News\Store;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            //traemos paginadas las noticias
            $news = News::paginate(10);
            //retornamos la respuesta
            return ApiResponse::success('Listado de noticias', Response::HTTP_OK, $news);

        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Store $request)
    {
        try{
            //creamos la noticia
            $news = new News($request->input());
            //subimos la imagen y guardamos la ruta en la variable $path
            $path = $request->image->store('public/images/news'); //sube los archivos en store/app/public/images/news
            //guardamos la ruta en la base de datos
            $news->image = $path;
            $news->save();
            //retornamos mensaje de exito
            return ApiResponse::success('Noticia creada correctamente', Response::HTTP_CREATED);

        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Error al crear la noticia', Response::HTTP_BAD_REQUEST);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            //buscamos la noticia por id
            $news = News::findOrFail($id);
            //retornamos la respuesta
            return ApiResponse::success('Detalle de noticia', Response::HTTP_OK, $news);
        }catch(ModelNotFoundException $e){
            //si no existe el id de la noticia retornamos un mensaje de error
            return ApiResponse::error('La noticia que busca no existe', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{

            //Busacamos la noticia por id que queremos eliminar
            $news = News::findOrFail($id);
            //guradamos la ruta de la imagen en la variable $path
            $path = $news->image;
            //eliminamos la noticia
            $news->delete();
            //si la noticia se elimino correctamente eliminamos la imagen
            if($news){
                //buscamos la imagen para eliminarla
                Storage::delete($path);
                return ApiResponse::success('Noticia eliminada correctamente', Response::HTTP_OK);
            }else{
                return ApiResponse::error('Error al eliminar la noticia', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('La noticia que desea eliminar no existe', Response::HTTP_NOT_FOUND);
        }
    }
}
