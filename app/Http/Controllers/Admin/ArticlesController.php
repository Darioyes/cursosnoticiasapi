<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Articles\Store;
use App\Models\Admin\Articles;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

use App\Http\Responses\ApiResponse;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            //relacionamos las tablas para traer el news_id y el article_image_id
            $articles = Articles::orderBy('id', 'desc')->paginate(10);
            //retornamos la respuesta
            return ApiResponse::success('Listado de articulos', Response::HTTP_OK, $articles);
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
            //creamos el articulo
            $articles = new Articles($request->input());
            //subimos la imagen y guardamos la ruta en la variable $path
            $path = $request->image->store('public/images/articles'); //sube los archivos en store/app/public/images/articles
            //guardamos la ruta en la base de datos
            $articles->image = $path;
            //guardamos el articulo
            $articles->save();
            //retornamos mensaje de exito
            return ApiResponse::success('Articulo creado correctamente', Response::HTTP_CREATED);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Error al crear el articulo', Response::HTTP_BAD_REQUEST);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            //relacionamos las tablas para traer el news_id 
            $articles = Articles::findOrFail($id);
            //retornamos la respuesta
            return ApiResponse::success('Articulo encontrado', Response::HTTP_OK, $articles);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Articulo no encontrado', Response::HTTP_NOT_FOUND);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Store $request, $id)
    {
        try{
            //buscamos el articulo
            $articles = Articles::findOrFail($id);
            //si existe la imagen
            if($request->hasFile('image')){
                //subimos la imagen y guardamos la ruta en la variable $path
                $path = $request->image->store('public/images/articles'); //sube los archivos en store/app/public/images/articles
                //eliminamos la imagen anterior
                Storage::delete($articles->image);
                //guardamos la ruta en la base de datos
                $articles->image = $path;
            }
            //actualizamos el articulo
            $articles->update($request->input());
            //retornamos mensaje de exito
            return ApiResponse::success('Articulo actualizado correctamente', Response::HTTP_OK);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Error al actualizar el articulo', Response::HTTP_BAD_REQUEST);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            //buscamos el articulo
            $articles = Articles::findOrFail($id);
            //guardamos la ruta de la imagen en la variable $path
            $path = $articles->image;
            //eliminamos el articulo
            $articles->delete();
            //si el articulo se elimino correctamente eliminamos la imagen
            if($articles){
                //buscamos la imagen para eliminarla
                Storage::delete($path);
                return ApiResponse::success('Articulo eliminado correctamente', Response::HTTP_OK);
            }else{
                return ApiResponse::error('Error al eliminar el articulo', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Articulo no encontrado', Response::HTTP_NOT_FOUND);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
