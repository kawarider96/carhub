<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Post(
 *     path="/api/register",
 *     summary="Új felhasználó regisztrációja",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"full_name","username","password","password_confirmation"},
 *             @OA\Property(property="full_name", type="string", example="Király Krisztián"),
 *             @OA\Property(property="username", type="string", example="neo"),
 *             @OA\Property(property="password", type="string", example="Password123!"),
 *             @OA\Property(property="password_confirmation", type="string", example="Password123!")
 *         )
 *     ),
 *     @OA\Response(response=201, description="Sikeres regisztráció"),
 *     @OA\Response(response=422, description="Érvénytelen adatok")
 * )
 */
class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'full_name'       => $request->full_name,
            'username'        => $request->username,
            'password'        => Hash::make($request->password),
            'role'            => 'user',
            'is_active'          => true,
            'locked'          => false,
            'failed_logins' => 0,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Sikeres regisztráció',
            'user'    => [
                'id'        => $user->id,
                'full_name' => $user->full_name,
                'username'  => $user->username,
                'role'      => $user->role,
            ],
            'token' => $token
        ], 201);
    }
}
