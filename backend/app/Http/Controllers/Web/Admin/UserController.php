<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Services\UserService;
use App\Models\User;
use App\Http\Requests\Admin\User\StoreUserRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public function __construct(
        protected UserService $users
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('admin', only: [
                'adminCreateForm',
                'adminStore',
                'adminEditForm',
                'adminUpdate',
            ]),
        ];
    }

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

    // ---- ADMIN: új felhasználó létrehozása (űrlap)
    public function adminCreateForm(): View
    {
        return view('pages.admin.users.create');
    }

    // ---- ADMIN: új felhasználó létrehozása (mentés)
    public function adminStore(StoreUserRequest $request): RedirectResponse
    {
        $this->users->adminCreate($request->validated());
        return redirect()->route('admin.dashboard.index')->with('success', 'Felhasználó létrehozva.');
    }

    public function adminEditForm(User $user): View
    {
        return view('pages.admin.users.edit', [ 'user' => $user ]);
    }

    public function adminUpdate(UpdateUserRequest $request, \App\Models\User $user): RedirectResponse
    {
        $validated = $request->validated();

        $normalize = function (string $key, $value) {
            return match ($key) {
                'is_active'     => filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? (bool)$value,
                'failed_logins' => is_numeric($value) ? (int) $value : $value,
                default         => $value,
            };
        };

        $changes = [];

        foreach ($validated as $key => $value) {
            if ($key === 'password') {
                if (!empty($value)) {
                    $changes['password'] = Hash::make($value);
                }
                continue;
            }

            $current = $user->{$key} ?? null;
            $new     = $normalize($key, $value);
            $curNorm = $normalize($key, $current);

            if ($new !== $curNorm) {
                $changes[$key] = $new;
            }
        }

        if (empty($changes)) {
            return back()->with('success', 'Nincs módosítás.');
        }

        $this->users->update($user->id, $changes);

        return redirect()->route('admin.dashboard.index')->with('success', 'Felhasználó frissítve.');
    }
}
