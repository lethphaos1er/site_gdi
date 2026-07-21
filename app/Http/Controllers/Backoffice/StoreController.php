<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class StoreController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Backoffice/StoreIndex', [
            'stores' => Store::query()
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'type' => [
                'required',
                Rule::in([
                    'bakery',
                    'italian',
                    'sport',
                ]),
            ],
            'identifier' => [
                'required',
                'string',
                'size:10',
                'alpha_num',
                'unique:stores,identifier',
            ],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Store::create($validated);

        return redirect()
            ->route('backoffice.stores.index');
    }
}