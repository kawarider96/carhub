<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public function __construct(
        protected UserService $service
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum'),
            new Middleware('active'),
            new Middleware('admin', only: [
                'index', 'store', 'destroy', 'adminUpdate', 'lock', 'unlock'
            ]),
        ];
    }

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Felhasználók listázása (admin)",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User"))
     *     )
     * )
     */
    public function index()
    {
        return UserResource::collection(
            $this->service->all()
        );
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     summary="Új felhasználó létrehozása (admin)",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Létrehozva",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function store(UserStoreRequest $request)
    {
        $user = $this->service->create($request->validated());

        return UserResource::make($user)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Felhasználó lekérése",
     *     tags={"Users"},
     *     @OA\Parameter(name="id", in="path", required=true),
     *     @OA\Response(
     *         response=200,
     *         description="User",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function show(int $id)
    {
        $user = $this->service->find($id);

        return UserResource::make($user);
    }

    /**
     * @OA\Patch(
     *     path="/users/me",
     *     summary="Saját profil módosítása",
     *     tags={"Users"},
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/UserUpdateRequest")),
     *     @OA\Response(response=200, @OA\JsonContent(ref="#/components/schemas/User"))
     * )
     */
    public function update(UserUpdateRequest $request)
    {
        $user = $this->service->update(auth()->id(), $request->validated());

        return UserResource::make($user);
    }

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     summary="Felhasználó törlése (admin)",
     *     tags={"Users"},
     *     @OA\Response(response=204)
     * )
     */
    public function destroy(int $id)
    {
        $this->service->delete($id);
        return response()->noContent();
    }

    /**
     * @OA\Post(
     *     path="/users/{id}/lock",
     *     summary="Felhasználó zárolása",
     *     tags={"Users"},
     *     @OA\Response(response=200, description="Zárolva")
     * )
     */
    public function lock(int $id)
    {
        $user = $this->service->lock($id);
        return UserResource::make($user);
    }

    /**
     * @OA\Post(
     *     path="/users/{id}/unlock",
     *     summary="Felhasználó feloldása",
     *     tags={"Users"},
     *     @OA\Response(response=200, description="Feloldva")
     * )
     */
    public function unlock(int $id)
    {
        $user = $this->service->unlock($id);
        return UserResource::make($user);
    }
}
