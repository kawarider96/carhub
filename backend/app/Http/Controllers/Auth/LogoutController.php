<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Kijelentkezés",
     *     description="Kilépteti az aktuálisan bejelentkezett felhasználót.",
     *     tags={"Auth"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres kijelentkezés",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     )
     * )
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['status' => true]);
    }
}
