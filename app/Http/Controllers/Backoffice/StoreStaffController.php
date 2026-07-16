<?php

namespace App\Http\Controllers\Backoffice;

use App\Enums\StoreRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\StoreStaffRequest;
use App\Http\Requests\Backoffice\UpdateStoreStaffRequest;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class StoreStaffController extends Controller
{
    public function index(Store $store): Response
    {
        Gate::authorize('manageStaff', $store);

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
        Gate::authorize('createEmployee', $store);

        $validated = $request->validated();

        if (
            ! $request->user()->isAdmin()
            && $validated['role'] !== StoreRole::EMPLOYEE->value
        ) {
            throw ValidationException::withMessages([
                'role' => 'Un patron peut uniquement ajouter un employé.',
            ]);
        }

        $store->users()->attach($validated['user_id'], [
            'role' => $validated['role'],
        ]);

        return back()->with(
            'success',
            'Le membre du personnel a été ajouté.'
        );
    }

    public function update(
        UpdateStoreStaffRequest $request,
        Store $store,
        User $user
    ): RedirectResponse {
        $currentRole = $this->staffRole($store, $user);

        abort_unless($currentRole !== null, 404);

        Gate::authorize('updateStaffRole', [$store, $user]);

        $newRole = $request->validated('role');

        if (
            $currentRole === StoreRole::OWNER->value
            && $newRole !== StoreRole::OWNER->value
            && $this->isLastOwner($store)
        ) {
            throw ValidationException::withMessages([
                'role' => 'Le dernier patron du magasin ne peut pas être rétrogradé.',
            ]);
        }

        $store->users()->updateExistingPivot($user->id, [
            'role' => $newRole,
        ]);

        return back()->with(
            'success',
            'Le rôle du membre du personnel a été modifié.'
        );
    }

    public function destroy(
        Store $store,
        User $user
    ): RedirectResponse {
        $currentRole = $this->staffRole($store, $user);

        abort_unless($currentRole !== null, 404);

        Gate::authorize('deleteStaff', [$store, $user]);

        if (
            $currentRole === StoreRole::OWNER->value
            && $this->isLastOwner($store)
        ) {
            throw ValidationException::withMessages([
                'user' => 'Le dernier patron du magasin ne peut pas être retiré.',
            ]);
        }

        $store->users()->detach($user->id);

        return back()->with(
            'success',
            'Le membre du personnel a été retiré.'
        );
    }

    private function staffRole(Store $store, User $user): ?string
    {
        $staffMember = $store->users()
            ->whereKey($user->getKey())
            ->first();

        return $staffMember?->pivot->role;
    }

    private function isLastOwner(Store $store): bool
    {
        return $store->users()
            ->wherePivot('role', StoreRole::OWNER->value)
            ->count() === 1;
    }
}