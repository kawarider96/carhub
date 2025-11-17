<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Class HomeController
 *
 * Felelős a publikus kezdőlap megjelenítéséért.
 */
class HomeController extends Controller
{
    /**
     * Kezdőoldal megjelenítése.
     *
     * @return View
     */
    public function index(): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->route('dashboard.index');
        }

        return view('pages.welcome');
    }
}
