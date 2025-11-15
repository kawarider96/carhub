<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserRequestResource;
use App\Models\UserRequest;
use App\Services\UserRequestService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Traits\ApiResponse;

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
    public function index()
    {
        $requests = UserRequestResource::collection($this->service->all());

        return $this->success($requests, 'Kérések listája', 200);
    }

    /**
     * @OA\Post(
     *   path="/requests",
     *   summary="Új törlési kérés indítása a saját fiókhoz (USER)",
     *   tags={"UserRequests"},
     *
     *   @OA\Response(
     *     response=201,
     *     description="Kérés létrehozva",
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
     *     response=409,
     *     description="Már létezik nyitott kérés",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   )
     * )
     */
    public function store()
    {
        $userId = auth()->id();

        $result = $this->service->createRequest($userId);

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
    public function approve(UserRequest $userRequest)
    {
        $approved = $this->service->approve($userRequest->id, auth()->id());

        return $this->success(
            UserRequestResource::make($approved),
            'Kérés jóváhagyva',
            200
        );
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
    public function reject(UserRequest $userRequest)
    {
        $rejected = $this->service->reject($userRequest->id, auth()->id());

        return $this->success(
            UserRequestResource::make($rejected),
            'Kérés elutasítva',
            200
        );
    }
}
