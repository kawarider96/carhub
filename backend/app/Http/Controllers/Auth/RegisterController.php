<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;

class RegisterController extends Controller
{
    public function __construct(
        protected AuthService $auth
    ) {}

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Új felhasználó regisztrációja",
     *     description="Regisztrál egy új felhasználót és visszaadja az adatait.",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Sikeres regisztráció",
     *         @OA\JsonContent(ref="#/components/schemas/RegisterResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = $this->auth->register($data);

        return response()->json([
            'status' => true,
            'user' => $user,
        ], 201);
    }
}
