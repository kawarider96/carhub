<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(
        protected AuthService $auth
    ) {}

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Felhasználó bejelentkezés",
     *     description="Hitelesíti a felhasználót felhasználónév és jelszó alapján.",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres bejelentkezés",
     *         @OA\JsonContent(ref="#/components/schemas/LoginResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Sikertelen bejelentkezés vagy zárolt fiók",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string',
        ]);

        $result = $this->auth->login(
            $request->username,
            $request->password
        );

        if (!$result['status']) {
            return response()->json([
                'message' => $result['error'] === 'locked'
                    ? 'A fiók zárolva van.'
                    : 'Érvénytelen bejelentkezési adatok.',
            ], 401);
        }

        return response()->json([
            'status' => true,
            'user' => $result['user']
        ]);
    }
}
