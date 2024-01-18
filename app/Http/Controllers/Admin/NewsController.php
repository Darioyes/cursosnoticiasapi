<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
            $news = News::with(['articles','category_news', 'category_course', 'comments'])
            ->orderBy('id', 'desc')
            ->paginate(10);
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
            //obtenemos el titulo para realizar el slug con guiones
            $title = $request->title;
            //creamos el slug
            $slug = str_replace(' ', '-', $title);
            //guardamos el slug en la base de datos
            $news->slug = $slug;
            //guardamos la noticia
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
            //si es noticia o curso hacemos la relación con eloquent
            //!estamos creando un objeto de la clase News y llamando a la función with
            //!dentro de la función with le pasamos un array con las relaciones que queremos hacer
            //!en este caso category_news y category_course que son las funciones que tenemos en el modelo News
            $news = News::with(['category_news', 'category_course'])
                        ->findOrFail($id)
                        ->makeHidden(['category_news_id', 'category_course_id']);
            //retornamos la respuesta
            return ApiResponse::success('Detalle de noticia', Response::HTTP_OK, $news);
            //buscamos la noticia por id
            // $news = News::findOrFail($id);
            // //retornamos la respuesta
            // return ApiResponse::success('Detalle de noticia', Response::HTTP_OK, $news);
        }catch(ModelNotFoundException $e){
            //si no existe el id de la noticia retornamos un mensaje de error
            return ApiResponse::error('La noticia que busca no existe', Response::HTTP_INTERNAL_SERVER_ERROR);
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
                'epigraph' => 'required|string|nullable|min:10|max:500',
                'title' => 'required|string|min:10|max:255|unique:news,title,'.$id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'content' => 'required|in:news,course',
                'featured' => 'required|numeric|in:1,0',
                'visible' => 'required|numeric|in:1,0',
                'category_news_id' => 'nullable|numeric|exists:categories_news,id',
                'category_course_id' => 'nullable|numeric|exists:categories_courses,id',
            ];
            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return ApiResponse::error('Error en la validación', Response::HTTP_BAD_REQUEST, $validator->errors()->all());
            }

            //buscamos la noticia por id
            $news = News::findOrFail($id);
            //si el usuario sube una imagen
            if($request->hasFile('image')){
                //guardamos la ruta de la imagen en la variable $path
                $path = $request->image->store('public/images/news');
                //eliminamos la imagen anterior
                Storage::delete($news->image);
                //guardamos la ruta de la nueva imagen
                $news->image = $path;
            }
            //obtenemos el titulo para realizar el slug con guiones
            $title = $request->title;
            //creamos el slug
            $slug = str_replace(' ', '-', $title);
            //guardamos el slug en la base de datos
            $news->slug = $slug;
            //actualizamos los datos
            $news->update($request->input());
            //retornamos mensaje de exito
            return ApiResponse::success('Noticia actualizada correctamente', Response::HTTP_OK, $news);
        }
        catch(ModelNotFoundException $e){
            //si no existe el id de la noticia retornamos un mensaje de error
            return ApiResponse::error('La noticia que desea modificar no existe', Response::HTTP_NOT_FOUND);
        }
        catch(\Exception $e){
            //si ocurre un error retornamos un mensaje de error
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
