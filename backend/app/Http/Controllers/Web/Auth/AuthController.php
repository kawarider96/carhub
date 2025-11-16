<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Class AuthController
 *
 * Kezeli a felhasználók és adminok bejelentkezését, regisztrációját és kijelentkezését.
 */
class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Felhasználói bejelentkezési oldal.
     *
     * @return View
     */
    public function showLoginForm(): View
    {
        return view('auth.userLogin');
    }

    /**
     * Felhasználói bejelentkezés (POST).
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Sikeres bejelentkezés.');
        }

        return back()->withErrors([
            'username' => 'Hibás felhasználónév vagy jelszó.',
        ]);
    }

    /**
     * Felhasználói regisztrációs oldal.
     *
     * @return View
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Felhasználói regisztráció (POST).
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name'  => ['required', 'string', 'max:255'],
            'username'   => ['required', 'string', 'max:255', 'unique:users,username'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $this->authService->register($validated);

        return redirect()->route('user.login')->with('success', 'Sikeres regisztráció. Jelentkezzen be.');
    }

    /**
     * Admin bejelentkezési oldal.
     *
     * @return View
     */
    public function showAdminLoginForm(): View
    {
        return view('auth.adminLogin');
    }

    /**
     * Admin bejelentkezés (POST).
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function adminLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (auth()->attempt($credentials)) {

            // Csak admin léphet be admin oldalra
            if (auth()->user()->role !== 'admin') {
                auth()->logout();
                return back()->withErrors([
                    'username' => 'Nincs jogosultsága az admin felületre.'
                ]);
            }

            $request->session()->regenerate();
            return redirect()->route('home')->with('success', 'Admin bejelentkezés sikeres.');
        }

        return back()->withErrors([
            'username' => 'Hibás admin felhasználónév vagy jelszó.',
        ]);
    }

    /**
     * Kijelentkezés.
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Sikeres kijelentkezés.');
    }
}
