<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class StoreController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Backoffice/StoreIndex', [
            'stores' => Store::query()
                ->orderBy('name', 'asc')
                ->get(),
        ]);
    }

    public function show(Store $store): Response
    {
        return Inertia::render('Backoffice/StoreShow', [
            'store' => [
                'id' => $store->id,
                'name' => $store->name,
                'address' => $store->address,
                'phone' => $store->phone,
                'email' => $store->email,
                'type' => $store->type,
                'identifier' => $store->identifier,
                'api_base_url' => $store->api_base_url,
                'owner_name' => $store->owner_name,
                'city' => $store->city,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'address' => ['required', 'string', 'max:80'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'api_base_url' => ['nullable', 'url', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:80'],
            'city' => ['required', 'string', 'max:80'],
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

        $validated['slug'] = Str::slug(
            $validated['name'] . '-' . $validated['city']
        );

        if (
            Store::query()
            ->where('slug', $validated['slug'])
            ->exists()
        ) {
            throw ValidationException::withMessages([
                'name' => 'Un magasin portant ce nom existe déjà dans cette ville.',
            ]);
        }

        Store::create($validated);

        return redirect()
            ->route('backoffice.stores.index')
            ->with('success', 'Le point de vente a bien été ajouté.');
    }

    public function update(
        Request $request,
        Store $store
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'address' => ['required', 'string', 'max:80'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'api_base_url' => ['nullable', 'url', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:80'],
            'city' => ['required', 'string', 'max:80'],
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
                Rule::unique('stores', 'identifier')
                    ->ignore($store->id),
            ],
        ]);

        $validated['slug'] = Str::slug(
            $validated['name'] . '-' . $validated['city']
        );

        if (
            Store::query()
            ->where('slug', $validated['slug'])
            ->whereKeyNot($store->id)
            ->exists()
        ) {
            throw ValidationException::withMessages([
                'name' => 'Un magasin portant ce nom existe déjà dans cette ville.',
            ]);
        }

        $store->update($validated);

        return redirect()
            ->route('backoffice.stores.show', $store)
            ->with('success', 'Les informations du magasin ont bien été enregistrées.');
    }

    public function destroy(Store $store): RedirectResponse
    {
        $store->delete();

        return redirect()
            ->route('backoffice.stores.index')
            ->with('success', 'Le point de vente a bien été supprimé.');
    }
}
