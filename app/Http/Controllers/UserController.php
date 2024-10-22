<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    use ApiResponseTrait;

    /**
     * @return JsonResponse
     */
    public function show() : JsonResponse
    {
        $user = auth()->user();
        if (!$user) {
            return $this->notFoundResponse(__('User not found'));
        }
        return $this->apiResponse(new UserResource($user), __('User retrieved successfully'));
    }

    /**
     * @param ProfileRequest $request
     * @return UserResource|JsonResponse
     */
    public function update(ProfileRequest $request) : UserResource|JsonResponse
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->update($request->only(['name', 'email', 'phone', 'password']));

        if (Redis::exists('user:' . $user->id)) {
            Redis::setex('user:' . $user->id, 86400, $user->toJson());
        }
        return $this->apiResponse(new UserResource($user), __('User has been modified successfully'), 201);

    }
}
