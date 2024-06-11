<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;


class ResetPasswordController extends Controller
{
    public function reset(Request $request)
    {
        $passwordRule = Rules\Password::defaults()->mixedCase()->numbers()->min(8)->required();
        $numbers = Rules\Password::defaults()->numbers();
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['confirmed',$passwordRule,$numbers],//password_confirmation,
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status == Password::PASSWORD_RESET
                    ? response()->json(['message' => __($status)], 200)
                    : response()->json(['error' => __($status)], 400);//cuando el token expira
    }
}
