<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Categories_courses;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

use App\Http\Responses\ApiResponse;
use App\Http\Requests\CategoriesCourses\Store;

class CategoriesCoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            //Traemos todas las categorias paginado en 10
            $categories_courses = Categories_courses::orderBy('name', 'asc')->paginate(10);
            //retornamos la respuesta
            return ApiResponse::success('Listado de categorias', Response::HTTP_OK, $categories_courses);
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
            $categories_courses = new Categories_courses($request->input());
            //obtenemos el titulo para realizar el slug con guiones
            $name = $request->name;
            //creamos el slug
            $slug = str_replace(' ', '-', $name);
            //guardamos el slug en la base de datos
            $categories_courses->slug = $slug;
            //guardamos la categoria
            $categories_courses->save();
            //retornamos mensaje de exito
            return ApiResponse::success('Categoría creada correctamente', Response::HTTP_CREATED);
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
            //buscamos la categoria por id
            $categories_courses = Categories_courses::findOrFail($id);
            //retornamos la respuesta
            return ApiResponse::success('Categoria encontrada', Response::HTTP_OK, $categories_courses);
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
                'name' => 'required|string|min:3|max:255|unique:categories_courses,name,'.$id,
            ];
            $validator = Validator::make($request->all(), $rules);
            //si falla la validacion retornamos el error
            if($validator->fails()){
                return ApiResponse::error($validator->errors(), Response::HTTP_BAD_REQUEST);
            }
            //buscamos la categoria por id
            $categories_courses = Categories_courses::findOrFail($id);
            //obtenemos el titulo para realizar el slug con guiones
            $name = $request->name;
            //creamos el slug
            $slug = str_replace(' ', '-', $name);
            //guardamos el slug en la base de datos
            $categories_courses->slug = $slug;
            //actualizamos la categoria
            $categories_courses->update($request->input());
            //retornamos mensaje de exito
            return ApiResponse::success('Categoria actualizada correctamente', Response::HTTP_OK);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Error al actualizar la categoria', Response::HTTP_BAD_REQUEST);
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
            //buscamos la categoria por id
            $categories_courses = Categories_courses::findOrFail($id);
            //obtenemos el nombre de la categoria
            $name = $categories_courses->name;
            //eliminamos la categoria
            $categories_courses->delete();
            //retornamos mensaje de exito
            return ApiResponse::success('Categoria '.$name.' eliminada correctamente', Response::HTTP_OK);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Error al eliminar la categoria', Response::HTTP_BAD_REQUEST);
        }
    }
}
