<?php

namespace App\Http\Controllers\Users;


use App\Models\Users\Categories_news as CategoriesNewsFront;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;


class CategoriesNewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //obtener las categorias de las noticias
        try{
            $categoriesNews = CategoriesNewsFront::orderBy('name', 'asc')
            ->get();
            //retornamos la respuesta
            return ApiResponse::success('Listado de categorias de noticias', Response::HTTP_OK, $categoriesNews);
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
    public function show(CategoriesNewsFront $categoriesNewsFront)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoriesNewsFront $categoriesNewsFront)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoriesNewsFront $categoriesNewsFront)
    {
        //
    }
}
