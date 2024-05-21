<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Contact;

use App\Http\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\Contact\Store;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            //traer todos lo comentario de contacto paginado de 10 en 10 y del mas nuevo al mas reciente
            $contacts = Contact::orderBy('id', 'desc')->paginate(10);
            //retornamos la respueta
            return ApiResponse::success('Listado de comentarios de contacto', Response::HTTP_OK, $contacts);
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
            //creamos el comentario de contacto
            $contact = new Contact($request->input());
            //si se adjunto un documento lo guardamos en una carpeta privada
            if($request->file){
                $path = $request->file->store('private/contact');
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
    public function show($id)
    {
        try{
            //traemos el comentario de contacto
            $contact = Contact::findOrFail($id);
            //retornamos la respuesta
            return ApiResponse::success('Comentario ', Response::HTTP_OK, $contact);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Comentario no encontrado', Response::HTTP_NOT_FOUND);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Store $request, $id)
    {
        // try{
        //     //traemos el comentario de contacto
        //     $contact = Contact::findOrFail($id);
        //     //actualizamos el comentario de contacto
        //     $contact->update($request->input());
        //     //si se adjunto un documento lo guardamos en una carpeta privada
        //     if($request->file){
        //         $path = $request->file->store('private/contact');
        //         //eliminamos el archivo anterior
        //         Storage::delete($contact->file);
        //         //guardamos el nuevo archivo en la variable
        //         $contact->file = $path;
        //     }
        //     //guardamos el comentario de contacto
        //     $contact->save();
        //     //retornamos mensaje de exito
        //     return ApiResponse::success('Comentario actualizado correctamente', Response::HTTP_OK);
        // }catch(ModelNotFoundException $e){
        //     return ApiResponse::error('Comentario no encontrado', Response::HTTP_NOT_FOUND);
        // }catch(\Exception $e){
        //     return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            //traemos el comentario de contacto
            $contact = Contact::findOrFail($id);
            //guardamos la ruta del archivo en la variable
            $path = $contact->file;
            //eliminamos el comentario de contacto
            $contact->delete();
            //si el comentario de contacto se elimino correctamente eliminamos el archivo
            if($contact){
                //buscamos el archivo para eliminarlo
                Storage::delete($path);
                return ApiResponse::success('Comentario eliminado correctamente', Response::HTTP_OK);
            }else{
                return ApiResponse::error('Error al eliminar el comentario', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Comentario no encontrado', Response::HTTP_NOT_FOUND);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //funcion para descargar el archivo de la carpeta privada
 public function download($id)
{
    try {
        // Traemos el comentario de contacto
        $contact = Contact::findOrFail($id);

        // Verificamos que el archivo no sea null
        if (!$contact->file) {
            return ApiResponse::error('Archivo no encontrado', Response::HTTP_NOT_FOUND);
        }

        // Ruta completa del archivo en el almacenamiento privado
        $filePath =  $contact->file;

        // Verificamos que el archivo exista
        if (!Storage::disk('local')->exists($filePath)) {
            return ApiResponse::error('Archivo no encontrado', Response::HTTP_NOT_FOUND);
        }

        // Retornamos el archivo
        return Storage::disk('local')->download($filePath);
    } catch (ModelNotFoundException $e) {
        return ApiResponse::error('Comentario no encontrado', Response::HTTP_NOT_FOUND);
    } catch (\Exception $e) {
        return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}


}
