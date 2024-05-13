<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\Users\User as UserFront;
use App\Http\Requests\Auth\Create;
use App\Http\Requests\Auth\Login;
use App\Http\Responses\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;



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

        //$token = $user->createToken('noti_token')->plainTextToken;

       //si el save fue exitoso
        if($user){
            return ApiResponse::successAuth('Usuario creado correctamente', Response::HTTP_CREATED);
        }else{
            return ApiResponse::error('Error al crear el usuario', Response::HTTP_BAD_REQUEST);
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
    public function update(Request $request, $id)
    {
        try{
            $rules = [
                'name' => 'required|min:3|max:100',
                'lastname' => 'required|min:3|max:100',
                'email' => 'required|email|unique:users,email,'.$id,
            ];

            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return ApiResponse::error('Error en la validación', Response::HTTP_BAD_REQUEST, $validator->errors()->all());
            }

            $user = UserFront::findOrFail($id);
            $user->fill($request->input());
            $user->save();

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
}
