<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * @OA\Post(
 *     path="/api/login",
 *     tags={"Auth"},
 *     summary="Bejelentkezés",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username","password"},
 *             @OA\Property(property="username", type="string", example="krisz"),
 *             @OA\Property(property="password", type="string", example="Password123!")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Sikeres bejelentkezés"),
 *     @OA\Response(response=401, description="Hibás adatok"),
 *     @OA\Response(response=423, description="Fiók zárolva")
 * )
 */
class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        /** @var User $user */
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json(['message' => 'Hibás felhasználónév vagy jelszó'], 401);
        }

        //Fiók zárolva?
        if (!$user->is_active) {
            return response()->json(['message' => 'A fiók zárolva van'], 423);
        }

        //Jelszó ellenőrzés
        if (!Hash::check($request->password, $user->password)) {

            $user->failed_logins++;

            if ($user->failed_logins >= 5) {
                $user->is_active = true;
            }

            $user->save();

            return response()->json(['message' => 'Hibás felhasználónév vagy jelszó'], 401);
        }

        //reset failed logins
        $user->failed_logins = 0;
        $user->save();

        //Token létrehozása
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Sikeres bejelentkezés',
            'token'   => $token,
            'user'    => [
                'id'        => $user->id,
                'full_name' => $user->full_name,
                'role'      => $user->role,
            ]
        ], 200);
    }
}
