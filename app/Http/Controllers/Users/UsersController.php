<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Auth\Events\Registered;

use App\Models\Users\User as UserFront;
use App\Http\Requests\Auth\Create;
use App\Http\Requests\Auth\Login;
use App\Http\Responses\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Auth\Update;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Create $request)
    {
        try{
            $user = new UserFront($request->input());
            //lo que llega en la varieble name lo convertimos a minusculas
            $user->name = strtolower($request->name);
            $user->lastname = strtolower($request->lastname);
            //dejamos la primera letra en mayusculas de las plabras
            $user->name = ucwords($request->name);
            $user->lastname = ucwords($request->lastname);
            //hasheamos la contraseña
            $user->password = Hash::make($request->password);
            $user->save();
            //creamos el token
            $token = $user->createToken('noti_token')->plainTextToken;

           //si el save fue exitoso
            if($user){
                return ApiResponse::successAuth('Usuario registrado correctamente', Response::HTTP_CREATED, $token, $user);
            }else{
                return ApiResponse::error('Error al registrar el usuario', Response::HTTP_BAD_REQUEST);
            }


        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Error de la base de datos', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $user = UserFront::findOrFail($id);
            return ApiResponse::success('Detalle de usuario', Response::HTTP_OK, $user);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('El usuario que busca no existe', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request, $id)
    {
        try{
            $user = UserFront::findOrFail($id);
            $user->fill($request->input());
            $user->save();

            if($user){
                return ApiResponse::success('Usuario actualizado correctamente', Response::HTTP_OK, $user);
            }else{
                return ApiResponse::error('Error al actualizar el usuario', Response::HTTP_BAD_REQUEST);
            }

            //$user->update($request->input());

            return ApiResponse::success('Usuario actualizado correctamente', Response::HTTP_OK, $user);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('El usuario que desea modificar no existe', Response::HTTP_NOT_FOUND);
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
            //eliminamos el usuario
            $user = UserFront::findOrFail($id);
            $name = $user->name;
            $user->delete();

            return ApiResponse::success('Usuario '.$name.' fue eliminado correctamente', Response::HTTP_OK);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('El usuario que desea eliminar no existe', Response::HTTP_NOT_FOUND);
        }
    }

    // //funcion verificar email
    // public function verify($id, $hash){
    //     try{

    //         //buscamos el usuario
    //         $user = UserFront::findOrFail($id);
    //         //verificamos si el email ya ha sido verificado
    //         if($user->hasVerifiedEmail()){
    //             return ApiResponse::error('El email ya ha sido verificado', Response::HTTP_BAD_REQUEST);
    //         }
    //         //obtenemos el token de la tabla personal_access_tokens de la base de datos

    //         //verificamos el email
    //         if($user->markEmailAsVerified()){
    //             return ApiResponse::success('Email verificado correctamente', Response::HTTP_OK);
    //         }else{
    //             return ApiResponse::error('Error al verificar el email', Response::HTTP_INTERNAL_SERVER_ERROR);
    //         }

    //     }catch(ModelNotFoundException $e){
    //         return ApiResponse::error('El usuario que desea verificar no existe', Response::HTTP_NOT_FOUND);
    //     }
    // }

    //funcion para resetear password
    public function resetPassword(Request $request){
        try{
            $rules = [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:8|max:100',
            ];
            //validamos los datos
            $validator = Validator::make($request->all(), $rules);
            //si falla la validacion
            if($validator->fails()){
                return ApiResponse::error('Error en la validación', Response::HTTP_BAD_REQUEST, $validator->errors()->all());
            }
            //buscamos el usuario
            $user = UserFront::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            return ApiResponse::success('Contraseña actualizada correctamente', Response::HTTP_OK);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('El email que ingreso no existe', Response::HTTP_NOT_FOUND);
        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Login $request){


        //buscamos en la base de deatos con attempt que el email y el password sean correctos
        if(!Auth::attempt($request->only('email', 'password'))){
            return ApiResponse::error('Usuario y/o contraseña incorrectas', Response::HTTP_UNAUTHORIZED);
        }

        $user = UserFront::where('email', $request->email)->first();
        $token = $user->createToken('noti_token')->plainTextToken;
        return ApiResponse::successAuth('Sesión iniciada correctamente', Response::HTTP_OK, $token, $user);
    }

    public function logout(){
        //eliminamos el token de la base de datos desde la autenticacion de sanctum
        auth()->user()->tokens()->delete();

        return ApiResponse::success('Sesión cerrada correctamente', Response::HTTP_OK);
    }

    public function verifyEmail(Request $request)
    {
        try{
               // Verificar la autenticidad de la solicitud y redirigir al local host 4200
        if (!$request->hasValidSignature()) {
            return ApiResponse::error('Enlace de verificación no válido', Response::HTTP_BAD_REQUEST);
        }

        // Buscar y verificar el usuario
        $user = UserFront::findOrFail($request->id);
        if (!$user) {
            return ApiResponse::error('Enlace de verificación no válido', Response::HTTP_BAD_REQUEST);
        }

        // Marcar el correo electrónico del usuario como verificado
        //$user->markEmailAsVerified();
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        // Redirigir al frontend
        return Redirect::away('http://localhost:4200');
        return ApiResponse::success('Correo electrónico verificado correctamente', Response::HTTP_OK);

        }catch(\Exception $e){
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }catch(ModelNotFoundException $e){
            return ApiResponse::error('Error de la base de datos', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
