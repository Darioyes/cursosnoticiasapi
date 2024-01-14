<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\Admin\User;
use App\Http\Requests\Auth\Create;
use App\Http\Requests\Auth\Login;
use App\Http\Responses\ApiResponse;

class UsersController extends Controller
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
    public function store(Create $request)
    {
        $user = new User($request->input());
        $user->password = Hash::make($request->password);
        $user->save();

        $token = $user->createToken('noti_token')->plainTextToken;

       //si el save fue exitoso
        if($user){
            return ApiResponse::successAuth('Usuario creado correctamente', Response::HTTP_CREATED, $token, $user);
        }else{
            return ApiResponse::error('Error al crear el usuario', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function login(Login $request){

        //verificamos el guard que sea admin
        $guard = $request->is_admin('admin') ? 'admin' : 'user';
        //buscamos en la base de deatos con attempt que el email y el password sean correctos
        if(!Auth::guard($guard)->attempt($request->only('email','password'))){
            //si no lo son devolvemos un error
            return ApiResponse::error('Usuario y/o contraseña incorrectas', Response::HTTP_UNAUTHORIZED);
        }
        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('noti_token')->plainTextToken;
        return ApiResponse::successAuth('Sesión iniciada correctamente', Response::HTTP_OK, $token, $user);
    }

    // public function logout(){
    //     //eliminamos el token de la base de datos desde la autenticacion de sanctum
    //     auth()->user()->tokens()->delete();
        
    //     return ApiResponse::success('Sesión cerrada correctamente', Response::HTTP_OK);
    // }
}
