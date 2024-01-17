<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Categories_news;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

use App\Http\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\CategoriesNews\Store;

class CategoriesNewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            //Traemos todas las categorias paginado en 10
            $categories_news = Categories_news::paginate(10);
            //retornamos la respuesta
            return ApiResponse::success('Listado de categorias', Response::HTTP_OK, $categories_news);
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
            //creamos la categoria
            $categories_news = new Categories_news($request->input());
            //obtenemos el titulo para realizar el slug con guiones
            $name = $request->name;
            //creamos el slug
            $slug = str_replace(' ', '-', $name);
            //guardamos el slug en la base de datos
            $categories_news->slug = $slug;
            //guardamos la categoria
            $categories_news->save();
            //retornamos mensaje de exito
            return ApiResponse::success('Categoria creada correctamente', Response::HTTP_CREATED);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Error al crear la categoria', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            //traemos la categoria
            $categories_news = Categories_news::findOrFail($id);
            //retornamos la respuesta
            return ApiResponse::success('Categoria encontrada', Response::HTTP_OK, $categories_news);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Categoria no encontrada', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try{
            //validamos los datos
            $rules = [
                'name' => 'required|min:3|max:100|unique:categories_news,name,'.$id,
            ];
            //creamos una instancia del validador
            $validator = Validator::make($request->all(), $rules);
            //si hay error en la validacion
            if($validator->fails()){
                return ApiResponse::error($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            //buscamos la categoria
            $categories_news = Categories_news::findOrFail($id);
            //obtenemos el titulo para realizar el slug con guiones
            $name = $request->name;
            //creamos el slug
            $slug = str_replace(' ', '-', $name);
            //guardamos el slug en la base de datos
            $categories_news->slug = $slug;
            //actualizamos la categoria
            $categories_news->update($request->input());
            //retornamos mensaje de exito
            return ApiResponse::success('Categoria actualizada correctamente', Response::HTTP_OK);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Error al actualizar la categoria', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            //buscamos la categoria
            $categories_news = Categories_news::findOrFail($id);
            //eliminamos la categoria
            $categories_news->delete();
            //retornamos mensaje de exito
            return ApiResponse::success('Categoria eliminada correctamente', Response::HTTP_OK);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Error al eliminar la categoria', Response::HTTP_BAD_REQUEST);
        }
    }
}
