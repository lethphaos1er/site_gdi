<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\StoreStaffRequest;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class StoreStaffController extends Controller
{
    public function index(Store $store): Response
    {
        return Inertia::render('Backoffice/StoreStaff', [
            'store' => [
                'id' => $store->id,
                'name' => $store->name,
                'slug' => $store->slug,
                'city' => $store->city,
                'type' => $store->type,
            ],

            'staff' => $store->users()
                ->select([
                    'users.id',
                    'users.name',
                    'users.email',
                ])
                ->orderBy('users.name')
                ->get()
                ->map(fn (User $user): array => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->pivot->role,
                ]),
        ]);
    }

    public function store(
        StoreStaffRequest $request,
        Store $store
    ): RedirectResponse {
        $validated = $request->validated();

        $store->users()->attach($validated['user_id'], [
            'role' => $validated['role'],
        ]);

        return back()->with(
            'success',
            'Le membre du personnel a été ajouté.'
        );
    }
}