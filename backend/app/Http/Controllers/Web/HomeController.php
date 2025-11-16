<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

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
    public function index(): View
    {
        return view('welcome');
    }
}
