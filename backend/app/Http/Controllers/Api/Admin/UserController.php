<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class UserController extends Controller implements HasMiddleware
{
    use ApiResponse;
    use AuthorizesRequests;

    public function __construct(
        protected UserService $service
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('admin', only: [
                'index', 'adminStore', 'destroy', 'adminUpdate', 'lock', 'unlock', 'show'
            ]),
        ];
    }

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Felhasználók listázása (admin)",
     *     tags={"Users"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Felhasználók listája",
     *         @OA\JsonContent(ref="#/components/schemas/UserListResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Nincs bejelentkezve",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Nincs jogosultság (admin required)",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $users = UserResource::collection($this->service->all());

        return $this->success($users, 'Felhasználók listája', 200);
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     summary="Új felhasználó létrehozása (admin)",
     *     tags={"Users"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserStoreRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Felhasználó létrehozva",
     *         @OA\JsonContent(ref="#/components/schemas/UserSingleResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Nincs bejelentkezve",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Nincs jogosultság (admin required)",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validációs hiba",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     )
     * )
     */
    public function adminStore(StoreUserRequest $request): JsonResponse
    {
        $user = $this->service->register($request->validated());

        return $this->success(UserResource::make($user), 'Felhasználó sikeresen létrehozva', 201);
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Felhasználó lekérése (admin)",
     *     tags={"Users"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Felhasználó adatai",
     *         @OA\JsonContent(ref="#/components/schemas/UserSingleResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Nincs bejelentkezve",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Nincs jogosultság (admin required)",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="A felhasználó nem található",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function show(User $user): JsonResponse
    {
        return $this->success(UserResource::make($user), 'Felhasználó adatai', 200);
    }

    /**
     * @OA\Put(
     *     path="/users",
     *     summary="Saját profil módosítása",
     *     tags={"Users"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserUpdateRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Profil frissítve",
     *         @OA\JsonContent(ref="#/components/schemas/UserSingleResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Nincs bejelentkezve",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validációs hiba",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     )
     * )
     */
    public function update(UpdateUserRequest $request): JsonResponse
    {
        $this->authorize('update', auth()->user());

        $user = $this->service->update(auth()->id(), $request->validated());

        return $this->success(UserResource::make($user), 'Profil sikeresen frissítve', 200);
    }

    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     summary="Felhasználó módosítása (admin)",
     *     tags={"Users"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="A módosítandó felhasználó azonosítója",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserUpdateRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Felhasználó sikeresen frissítve",
     *         @OA\JsonContent(ref="#/components/schemas/UserSingleResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Nincs bejelentkezve",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Nincs jogosultság a művelethez",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="A felhasználó nem található",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validációs hiba",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     )
     * )
     */
    public function adminUpdate(UpdateUserRequest $request, User $user): JsonResponse
    {
        $updated = $this->service->update($user->id, $request->validated());

        return $this->success(UserResource::make($updated), 'Felhasználó frissítve', 200);
    }

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     summary="Felhasználó törlése (admin)",
     *     tags={"Users"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres törlés",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Nincs bejelentkezve",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Nincs jogosultság (admin required)",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="A felhasználó nem található",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function destroy(User $user): JsonResponse
    {
        $this->service->delete($user->id);

        return $this->success(null, 'Felhasználó sikeresen törölve', 200);
    }

    /**
     * @OA\Post(
     *     path="/users/{id}/lock",
     *     summary="Felhasználó zárolása (admin)",
     *     tags={"Users"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Felhasználó zárolva",
     *         @OA\JsonContent(ref="#/components/schemas/UserSingleResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Nincs bejelentkezve",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Nincs jogosultság (admin required)",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="A felhasználó nem található",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function lock(User $user): JsonResponse
    {
        $locked = $this->service->lock($user->id);

        return $this->success(UserResource::make($locked), 'Felhasználó zárolva', 200);
    }

    /**
     * @OA\Post(
     *     path="/users/{id}/unlock",
     *     summary="Felhasználó feloldása (admin)",
     *     tags={"Users"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Felhasználó feloldva",
     *         @OA\JsonContent(ref="#/components/schemas/UserSingleResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Nincs bejelentkezve",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Nincs jogosultság (admin required)",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="A felhasználó nem található",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function unlock(User $user): JsonResponse
    {
        $unlocked = $this->service->unlock($user->id);

        return $this->success(UserResource::make($unlocked), 'Felhasználó feloldva', 200);
    }
}
