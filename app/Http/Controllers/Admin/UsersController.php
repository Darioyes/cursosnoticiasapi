<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Admin\User;
use App\Http\Requests\Auth\Create;
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
}
