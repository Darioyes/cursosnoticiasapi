<?php

namespace App\Http\Controllers\Users;

use App\Models\Users\Comments as CommentsFront;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Responses\ApiResponse;

use App\Http\Requests\Comments\Store;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Store $request)
    {
        try{
            //creamos el comentario
            $comments = new CommentsFront($request->input());
            //guardamos el comentario
            $comments->save();
            //retornamos el mensaje de exito
            return ApiResponse::success('Comentario creado correctamente', Response::HTTP_CREATED);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Error al crear el comentario', Response::HTTP_BAD_REQUEST);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CommentsFront $commentsFront)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CommentsFront $commentsFront)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommentsFront $commentsFront)
    {
        //
    }
}
