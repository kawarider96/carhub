<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Post(
 *     path="/api/logout",
 *     summary="Kijelentkezés",
 *     tags={"Auth"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(response=200, description="Sikeres kijelentkezés")
 * )
 */
class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sikeresen kijelentkeztél'
        ]);
    }
}
