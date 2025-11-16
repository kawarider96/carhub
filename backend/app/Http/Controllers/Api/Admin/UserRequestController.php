<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserRequestResource;
use App\Models\UserRequest;
use App\Services\UserRequestService;
use App\Http\Requests\UserRequest\StoreUserRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class UserRequestController extends Controller implements HasMiddleware
{
    use ApiResponse;

    public function __construct(
        protected UserRequestService $service
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('admin', only: ['index', 'approve', 'reject']),
        ];
    }

    /**
     * @OA\Get(
     *   path="/requests",
     *   summary="Összes felhasználói kérés listázása (ADMIN)",
     *   tags={"UserRequests"},
     *
     *   @OA\Response(
     *     response=200,
     *     description="Kérések listája",
     *     @OA\JsonContent(ref="#/components/schemas/UserRequestListResponse")
     *   ),
     *
     *   @OA\Response(
     *     response=401,
     *     description="Nincs bejelentkezve",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   ),
     *
     *   @OA\Response(
     *     response=403,
     *     description="Nincs jogosultság (admin required)",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   )
     * )
     */
    public function index(): JsonResponse
    {
        $requests = UserRequestResource::collection($this->service->all());

        return $this->success($requests, 'Kérések listája', 200);
    }

    /**
     * @OA\Post(
     *   path="/requests",
     *   summary="Felhasználói kérés indítása (delete_account | missing_brand)",
     *   tags={"UserRequests"},
     *
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *           required={"type"},
     *           @OA\Property(property="type", type="string", enum={"delete_account", "missing_brand"}),
     *           @OA\Property(property="payload", type="object", nullable=true)
     *       )
     *   ),
     *
     *   @OA\Response(
     *     response=201,
     *     description="Kérés sikeresen létrehozva",
     *     @OA\JsonContent(ref="#/components/schemas/UserRequestSingleResponse")
     *   ),
     *
     *   @OA\Response(
     *     response=401,
     *     description="Nincs bejelentkezve",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   ),
     *
     *   @OA\Response(
     *     response=403,
     *     description="Admin felhasználó nem hozhat létre kérelmet",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   ),
     *
     *   @OA\Response(
     *     response=409,
     *     description="Már létezik nyitott ugyanilyen típusú kérés",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   )
     * )
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = auth()->user();

        // Admin nem hozhat létre kérést
        if ($user->role === 'admin') {
            return $this->error('Admin nem indíthat kérelmet', 403);
        }

        $validated = $request->validated();

        $type    = $validated['type'];
        $payload = $validated['payload'] ?? [];

        $result = $this->service->createRequest($user->id, $type, $payload);

        if (!$result) {
            return $this->error('Már létezik nyitott kérés', 409);
        }

        return $this->success(UserRequestResource::make($result), 'Kérés sikeresen létrehozva', 201);
    }

    /**
     * @OA\Post(
     *   path="/requests/{userRequest}/approve",
     *   summary="Felhasználói kérés jóváhagyása (ADMIN)",
     *   tags={"UserRequests"},
     *
     *   @OA\Parameter(
     *     name="userRequest",
     *     in="path",
     *     required=true,
     *     description="A jóváhagyandó kérés ID-je",
     *     @OA\Schema(type="integer")
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Sikeres jóváhagyás",
     *     @OA\JsonContent(ref="#/components/schemas/UserRequestSingleResponse")
     *   ),
     *
     *   @OA\Response(
     *     response=401,
     *     description="Nincs bejelentkezve",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   ),
     *
     *   @OA\Response(
     *     response=403,
     *     description="Nincs jogosultság (admin required)",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   ),
     *
     *   @OA\Response(
     *     response=404,
     *     description="A kérés nem található",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   )
     * )
     */
    public function approve(UserRequest $userRequest): JsonResponse
    {
        $approved = $this->service->approve($userRequest->id, auth()->id());

        return $this->success(UserRequestResource::make($approved), 'Kérés jóváhagyva', 200);
    }

    /**
     * @OA\Post(
     *   path="/requests/{userRequest}/reject",
     *   summary="Felhasználói kérés elutasítása (ADMIN)",
     *   tags={"UserRequests"},
     *
     *   @OA\Parameter(
     *     name="userRequest",
     *     in="path",
     *     required=true,
     *     description="Az elutasítandó kérés ID-je",
     *     @OA\Schema(type="integer")
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Sikeres elutasítás",
     *     @OA\JsonContent(ref="#/components/schemas/UserRequestSingleResponse")
     *   ),
     *
     *   @OA\Response(
     *     response=401,
     *     description="Nincs bejelentkezve",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   ),
     *
     *   @OA\Response(
     *     response=403,
     *     description="Nincs jogosultság (admin required)",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   ),
     *
     *   @OA\Response(
     *     response=404,
     *     description="A kérés nem található",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   )
     * )
     */
    public function reject(UserRequest $userRequest): JsonResponse
    {
        $rejected = $this->service->reject($userRequest->id, auth()->id());

        return $this->success(UserRequestResource::make($rejected), 'Kérés elutasítva', 200);
    }
}
