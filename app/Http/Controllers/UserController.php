<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * GET /api/v1/users/profile
     * Returns the authenticated user including report statistics.
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user()->loadCount('reports');

        return response()->json([
            'success' => true,
            'data' => (UserResource::withStatistics())->toArray($request),
        ]);
    }

    /**
     * PUT /api/v1/users/profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone' => ['sometimes', 'required', 'string', 'max:15'],
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $user->update($request->only(['name', 'phone']));

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => (new UserResource($user->fresh()))->toArray($request),
        ]);
    }
}
