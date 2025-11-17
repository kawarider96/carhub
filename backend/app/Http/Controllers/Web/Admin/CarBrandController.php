<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CarBrand\StoreCarBrandRequest;
use App\Services\CarBrandService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CarBrandController extends Controller implements HasMiddleware
{
    public function __construct(
        protected CarBrandService $brands
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('admin', only: ['create', 'store']),
        ];
    }
    
    public function create(): View
    {
        return view('pages.admin.brands.create');
    }

    public function store(StoreCarBrandRequest $request): RedirectResponse
    {
        $this->brands->create($request->validated());
        return redirect()->route('admin.dashboard.index')->with('success', 'Márka létrehozva.');
    }
}

