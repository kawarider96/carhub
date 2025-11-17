<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        protected UserService $users
    ) {}

    public function profile(): View
    {
        $user = Auth::user();
        return view('pages.user.profil', [
            'user' => $user,
        ]);
    }

    public function changePasswordForm(): View
    {
        return view('pages.user.change_password');
    }

    public function changePassword(UpdateUserRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if (!Hash::check(request('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'A jelenlegi jelszó nem megfelelő.']);
        }

        $validated = $request->validated();

        if (!isset($validated['password'])) {
            return back()->withErrors(['password' => 'Az új jelszó megadása kötelező.']);
        }

        $this->users->update($user->id, [
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.show')->with('success', 'A jelszó sikeresen megváltoztatva.');
    }
}
