<?php

namespace App\Http\Controllers;

use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendResetLinkEmail(Request $request) : JsonResponse
    {
        $request->validate([
            'email' => 'required|email:rfc,dns'
        ]);

        $response = Password::sendResetLink($request->only('email'));


        return $response == Password::RESET_LINK_SENT
            ? response()->json(['message' => __('Reset link sent!')])
            : response()->json(['message' => __('Unable to send reset link.')], 400);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function reset(Request $request) : JsonResponse
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $response = Password::reset($request->only('email', 'password', 'token'), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
            event(new PasswordReset($user));
        });

        return $response == Password::PASSWORD_RESET
            ? response()->json(['message' => __('Password has been reset!')])
            : response()->json(['message' => __('Unable to reset password.')], 400);
    }
}
