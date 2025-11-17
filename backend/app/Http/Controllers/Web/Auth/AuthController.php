<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /* -------------------------------------------------------------
     | USER LOGIN (GET)
     ------------------------------------------------------------- */
    public function showLoginForm(): View
    {
        return view('pages.auth.userLogin');
    }

    /* -------------------------------------------------------------
     | USER LOGIN (POST)
     ------------------------------------------------------------- */
    public function login(LoginRequest $request): RedirectResponse
    {
        $result = $this->authService->login(
            $request->username,
            $request->password
        );

        // Login sikertelen
        if (!$result['status']) {
            return back()->withErrors([
                'username' => $result['error'] === 'locked'
                    ? 'A fiók zárolva lett 5 hibás próbálkozás miatt.'
                    : 'Hibás felhasználónév vagy jelszó.',
            ]);
        }

        $user = $result['user'];

        session()->regenerate();
        auth()->login($user);

        // Ha admin → admin dashboard
        if ($user->role === 'admin') {
            return redirect()
                ->route('admin.dashboard.index')
                ->with('success', 'Sikeres admin bejelentkezés.');
        }

        // Ha nem admin → user dashboard
        return redirect()
            ->route('dashboard.index')
            ->with('success', 'Sikeres bejelentkezés.');
    }

    /* -------------------------------------------------------------
     | USER REGISTRATION (GET)
     ------------------------------------------------------------- */
    public function showRegisterForm(): View
    {
        return view('pages.auth.register');
    }

    /* -------------------------------------------------------------
     | USER REGISTRATION (POST)
     ------------------------------------------------------------- */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $this->authService->register($request->validated());

        return redirect()
            ->route('auth.login')
            ->with('success', 'Sikeres regisztráció. Jelentkezzen be!');
    }

    /* -------------------------------------------------------------
     | ADMIN LOGIN (GET)
     ------------------------------------------------------------- */
    public function showAdminLoginForm(): View
    {
        return view('pages.auth.adminLogin');
    }

    /* -------------------------------------------------------------
     | ADMIN LOGIN (POST)
     ------------------------------------------------------------- */
    public function adminLogin(LoginRequest $request): RedirectResponse
    {
        $result = $this->authService->login(
            $request->username,
            $request->password
        );

        if (!$result['status']) {
            return back()->withErrors([
                'username' => 'Hibás felhasználónév vagy jelszó.',
            ]);
        }

        // Csak admin léphet be
        if ($result['user']->role !== 'admin') {
            return back()->withErrors([
                'username' => 'Nincs jogosultsága az admin felületre.',
            ]);
        }

        session()->regenerate();
        auth()->login($result['user']);

        return redirect()
            ->route('admin.dashboard.index')
            ->with('success', 'Admin bejelentkezés sikeres.');
    }

    /* -------------------------------------------------------------
     | LOGOUT
     ------------------------------------------------------------- */
    public function logout(): RedirectResponse
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Sikeres kijelentkezés.');
    }
}
