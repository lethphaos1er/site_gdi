<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\SearchStoreStaffUserRequest;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class StoreStaffSearchController extends Controller
{
    public function __invoke(
        SearchStoreStaffUserRequest $request,
        Store $store
    ): JsonResponse {
        Gate::authorize('manageStaff', $store);

        $search = trim($request->validated('search'));

        $users = User::query()
            ->select([
                'id',
                'first_name',
                'last_name',
                'email',
            ])
            ->whereDoesntHave(
                'stores',
                fn ($query) => $query->whereKey($store->id)
            )
            ->where(function ($query) use ($search) {
                $query
                    ->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereRaw(
                        "CONCAT(first_name, ' ', last_name) LIKE ?",
                        ["%{$search}%"]
                    );
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->limit(10)
            ->get()
            ->map(fn (User $user): array => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'full_name' => trim(
                    $user->first_name . ' ' . $user->last_name
                ),
                'email' => $user->email,
            ]);

        return response()->json([
            'users' => $users,
        ]);
    }
}