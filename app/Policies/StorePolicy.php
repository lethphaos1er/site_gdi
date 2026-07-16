<?php

namespace App\Policies;

use App\Enums\StoreRole;
use App\Models\Store;
use App\Models\User;

class StorePolicy
{
    /**
     * L’administrateur global possède toutes les autorisations.
     */
    public function before(User $user): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Voir la liste des magasins accessibles.
     */
    public function viewAny(User $user): bool
    {
        return $user->stores()->exists();
    }

    /**
     * Accéder au backoffice d’un magasin.
     */
    public function view(User $user, Store $store): bool
    {
        return $user->canAccessStore($store);
    }

    /**
     * Gérer les opérations du magasin :
     * stock, produits et rayons.
     */
    public function manage(User $user, Store $store): bool
    {
        return $user->canManageStore($store);
    }

    /**
     * Consulter la gestion du personnel.
     */
    public function manageStaff(User $user, Store $store): bool
    {
        return $user->canManageStaffAt($store);
    }

    /**
     * Ajouter un employé au magasin.
     *
     * Un owner peut uniquement ajouter un employee.
     * L’admin est autorisé automatiquement par before().
     */
    public function createEmployee(User $user, Store $store): bool
    {
        return $user->canManageStaffAt($store);
    }

    /**
     * Modifier le rôle d’un membre du personnel.
     *
     * Cette action est réservée aux administrateurs.
     * Les admins sont autorisés automatiquement par before().
     */
    public function updateStaffRole(
        User $user,
        Store $store,
        User $staffMember
    ): bool {
        return false;
    }

    /**
     * Retirer un membre du personnel du magasin.
     *
     * Un owner peut retirer uniquement un employee.
     * Il ne peut jamais retirer un autre owner.
     */
    public function deleteStaff(
        User $user,
        Store $store,
        User $staffMember
    ): bool {
        if (! $user->canManageStaffAt($store)) {
            return false;
        }

        if ($user->is($staffMember)) {
            return false;
        }

        return $staffMember->stores()
            ->whereKey($store->id)
            ->wherePivot(
                'role',
                StoreRole::EMPLOYEE->value
            )
            ->exists();
    }
}