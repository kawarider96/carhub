<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserRequestResource;
use App\Models\UserRequest;
use App\Services\UserRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserRequestApiController extends Controller implements HasMiddleware
{
    public function __construct(
        protected UserRequestService $service
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum'),
            new Middleware('active'),
            new Middleware('admin', only: ['index', 'approve', 'reject']),
        ];
    }

    /**
     * @OA\Get(
     *   path="/api/requests",
     *   summary="Összes nyitott felhasználói kérés listázása (ADMIN)",
     *   tags={"UserRequests"},
     *   security={{"sanctum":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Sikeres lekérdezés",
     *     @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/UserRequestResource"))
     *   )
     * )
     */
    public function index(): JsonResponse
    {
        $requests = $this->service->open();
        return response()->json(UserRequestResource::collection($requests));
    }

    /**
     * @OA\Post(
     *   path="/api/requests",
     *   summary="Új törlési kérés indítása a saját fiókhoz (USER)",
     *   tags={"UserRequests"},
     *   security={{"sanctum":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Kérés létrehozva",
     *     @OA\JsonContent(ref="#/components/schemas/UserRequestResource")
     *   ),
     *   @OA\Response(
     *     response=409,
     *     description="Már létezik nyitott kérés"
     *   )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $userId = auth()->id();

        $exists = $this->service->createRequest($userId);

        if (isset($exists['status']) && $exists['status'] === false) {
            return response()->json([
                'status' => false,
                'error' => 'already_exists'
            ], 409);
        }

        return response()->json(new UserRequestResource($exists));
    }

    /**
     * @OA\Get(
     *   path="/api/requests/me",
     *   summary="Saját felhasználói kérés lekérdezése (USER)",
     *   tags={"UserRequests"},
     *   security={{"sanctum":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Sikeres lekérdezés",
     *     @OA\JsonContent(ref="#/components/schemas/UserRequestResource")
     *   )
     * )
     */
    public function me(): JsonResponse
    {
        $req = $this->service->userRequest(auth()->id());
        return response()->json(new UserRequestResource($req));
    }

    /**
     * @OA\Post(
     *   path="/api/requests/{id}/approve",
     *   summary="Felhasználói kérés jóváhagyása (ADMIN)",
     *   tags={"UserRequests"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Sikeres jóváhagyás",
     *     @OA\JsonContent(ref="#/components/schemas/UserRequestResource")
     *   )
     * )
     */
    public function approve(int $id): JsonResponse
    {
        $this->authorize('approve', UserRequest::class);
        $adminId = auth()->id();

        $req = $this->service->approve($id, $adminId);

        return response()->json(new UserRequestResource($req));
    }

    /**
     * @OA\Post(
     *   path="/api/requests/{id}/reject",
     *   summary="Felhasználói kérés elutasítása (ADMIN)",
     *   tags={"UserRequests"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Sikeres elutasítás",
     *     @OA\JsonContent(ref="#/components/schemas/UserRequestResource")
     *   )
     * )
     */
    public function reject(int $id): JsonResponse
    {
        $this->authorize('reject', UserRequest::class);
        $adminId = auth()->id();

        $req = $this->service->reject($id, $adminId);

        return response()->json(new UserRequestResource($req));
    }
}
