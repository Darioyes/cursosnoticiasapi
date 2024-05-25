<?php

namespace App\Http\Controllers\Users;

use App\Models\Users\Categories_courses as CategoriesCoursesFront;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class CategoriesCoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //obtener las categorias de los cursos
        try{
            $categoriesCourses = CategoriesCoursesFront::orderBy('name', 'asc')
            ->get();
            //retornamos la respuesta
            return ApiResponse::success('Listado de categorias de cursos', Response::HTTP_OK, $categoriesCourses);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoriesCoursesFront $categoriesCoursesFront)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoriesCoursesFront $categoriesCoursesFront)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoriesCoursesFront $categoriesCoursesFront)
    {
        //
    }
}
