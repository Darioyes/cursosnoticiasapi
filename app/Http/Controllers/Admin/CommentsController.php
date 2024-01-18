<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Comments;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

use App\Http\Responses\ApiResponse;
use App\Http\Requests\Comments\Store;


class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            //trear todos los comentarios ordenado del mas nuevo al mas viejo
            $comments = Comments::orderBy('id', 'desc')->paginate(10);
            //retornamos el mensaje de exito
            return ApiResponse::success('Listado de comentarios', Response::HTTP_OK, $comments);

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
            //creamos el comentario
            $comments = new Comments($request->input());
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
    public function show($id)
    {
        try{
            //relacionamos las tablas para traer el news_id 
            $comments = Comments::with(['news'])->findOrFail($id);
            //retornamos el mensaje de exito
            return ApiResponse::success('Comentario encontrado', Response::HTTP_OK, $comments);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Error al encontrar el comentario', Response::HTTP_BAD_REQUEST);
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
            //relacionamos las tablas para traer el news_id 
            $comments = Comments::findOrFail($id);
            //actualizamos el comentario
            $comments->update($request->input());
            //retornamos el mensaje de exito
            return ApiResponse::success('Comentario actualizado correctamente', Response::HTTP_OK);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Error al actualizar el comentario', Response::HTTP_BAD_REQUEST);
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
            //relacionamos las tablas para traer el news_id 
            $comments = Comments::findOrFail($id);
            //eliminamos el comentario
            $comments->delete();
            //retornamos el mensaje de exito
            return ApiResponse::success('Comentario eliminado correctamente', Response::HTTP_OK);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Error al eliminar el comentario', Response::HTTP_BAD_REQUEST);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
