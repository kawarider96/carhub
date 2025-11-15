<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequests;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected UserService $service
    ) {}

    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Új felhasználó regisztrációja",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sikeres regisztráció",
     *         @OA\JsonContent(ref="#/components/schemas/UserSingleResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Érvénytelen adatok",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->service->register($request->validated());

        return $this->success(UserResource::make($user), 'Sikeres regisztráció', 201);
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Auth"},
     *     summary="Bejelentkezés",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres bejelentkezés",
     *         @OA\JsonContent(ref="#/components/schemas/LoginSuccessResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Hibás hitelesítési adatok",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=423,
     *         description="Fiók zárolva",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $result = $this->service->login($request->validated());

        if ($result['wrong_credentials']) {
            return $this->error('Hibás bejelentkezési adatok', 401);
        }

        if ($result['locked']) {
            return $this->error('A felhasználó zárolva', 423);
        }

        return $this->success($result, 'Sikeres bejelentkezés', 200);
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Kijelentkezés",
     *     tags={"Auth"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres kijelentkezés",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Sikeresen kijelentkeztél', 200);
    }
}
