<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache, DB, Mail};
use Illuminate\Support\Str;
use App\Http\Requests\{LoginRequest, RegisterRequest};
use App\Http\Resources\UserResource;
use App\Models\{User};
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{
    use ApiResponseTrait;

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request) : JsonResponse
    {
        DB::beginTransaction();
        $user  = User::create($request->validated());
        $otp   = Str::random(6);
        Cache::put('otp:' . $user->id, $otp, now()->addMinutes(10));
        Mail::to($user->email)->send(new SendOtpMail($otp, $user));
        Redis::setex('user:' . $user->id, 86400, json_encode($user));
        Auth::login($user);
        $data  = $this->getData($user);
        DB::commit();

        return $this->createResponse($data, __('User registered successfully'));

    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request) : JsonResponse
    {
        $credentials = $request->validated();
        if (!auth()->attempt($credentials)) {
            return $this->unauthorizedResponse('Invalid login credentials');
        }

        $user  = auth()->user();
        $data  = $this->getData($user);
        Redis::setex('user:' . $user->id, 86400, json_encode($user));
        return $this->createResponse($data, __('User logged in successfully'));
    }

    /**
     * @return JsonResponse
     */
    public function logout() : JsonResponse
    {
        $user = auth()->user();
        Redis::del('user:' . $user->id);
        $user->tokens()->delete();
        return $this->deleteResponse('User logged out successfully');
    }

    public function verifyOtp(Request $request) : JsonResponse
    {
        $request->validate([
            'otp' => 'required|string',
        ]);

        $user = $request->user();
        $cachedOtp = Cache::get('otp:' . $user->id);
        if (!$cachedOtp || $cachedOtp !== $request->otp) {
            return $this->unauthorizedResponse(__('Invalid OTP or OTP has expired.'));
        }
        $user->email_verified_at = now();
        $user->save();
        Cache::forget('otp:' . $user->id);

        return $this->createResponse(['user' => new UserResource($user)], 'Email verified successfully.');
    }

    /**
     * @param                      $token
     * @param Authenticatable|null $user
     * @return array
     */
    protected function getData(?Authenticatable $user) : array
    {
        $token = $user->createToken('Personal Access Token')->accessToken;
        return [
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => new UserResource($user)
        ];
    }

}
