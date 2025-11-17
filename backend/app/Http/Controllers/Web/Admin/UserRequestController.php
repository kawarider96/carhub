<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserRequest;
use App\Services\UserRequestService;
use App\Http\Requests\UserRequest\StoreUserRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class UserRequestController extends Controller
{
    public function __construct(
        protected UserRequestService $requests
    ) {}

    public function index(): View
    {
        $items = $this->requests->all();
        return view('pages.admin.requests.index', [ 'requests' => $items ]);
    }

    public function approve(UserRequest $userRequest): RedirectResponse
    {
        $this->requests->approve($userRequest->id, auth()->id());
        return back()->with('success', 'Kérés jóváhagyva.');
    }

    public function reject(UserRequest $userRequest): RedirectResponse
    {
        $this->requests->reject($userRequest->id, auth()->id());
        return back()->with('success', 'Kérés elutasítva.');
    }

    // ---- USER: create + store (missing_brand)
    public function create(): View
    {
        return view('pages.user.userRequest.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return back()->withErrors(['type' => 'Admin felhasználó nem hozhat létre kérelmet.']);
        }

        $data = $request->validated();
        $result = $this->requests->createRequest($user->id, $data['type'], $data['payload'] ?? []);

        if ($result === false) {
            return back()->withErrors(['type' => 'Már létezik nyitott ilyen típusú kérés.']);
        }

        return redirect()->route('dashboard.index')->with('success', 'Kérés sikeresen beküldve.');
    }
}
