<?php

namespace App\Http\Controllers\Users;

use App\Models\Users\Contact as ContactFront;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\Contact\Store;


class ContactController extends Controller
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
            //creamos el comentario de contacto
            $contact = new ContactFront($request->input());
            //si se adjunto un documento lo guardamos en una carpeta privada
            if($request->hasfile('file')){
                $path = $request->file('file')->store('private/contact');
                $contact->file = $path;
            }
            //guardamos el comentario de contacto
            $contact->save();
            //retornamos mensaje de exito
            return ApiResponse::success('Comentario creado correctamente', Response::HTTP_CREATED);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Error al crear el comentario de contacto', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactFront $contactFront)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactFront $contactFront)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactFront $contactFront)
    {
        //
    }
}
