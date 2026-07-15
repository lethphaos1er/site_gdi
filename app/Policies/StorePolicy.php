<?php

namespace App\Policies;

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
     * Gérer le personnel du magasin.
     */
    public function manageStaff(User $user, Store $store): bool
    {
        return $user->canManageStaffAt($store);
    }
}