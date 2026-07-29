<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Front/StoreSelection', [
            'stores' => Store::query()
                ->select([
                    'id',
                    'name',
                    'slug',
                    'city',
                    'type',
                ])
                ->orderBy('name', 'asc')
                ->get(),
        ]);
    }
}