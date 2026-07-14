<?php

namespace Tests\Feature;

use App\Enums\StoreRole;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_every_store(): void
    {
        $admin = User::factory()->create();

        $admin->forceFill([
            'is_admin' => true,
        ])->save();

        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($admin->canAccessStore($store));
        $this->assertTrue($admin->canManageStore($store));
        $this->assertTrue($admin->canManageStaffAt($store));
    }

    public function test_owner_can_manage_store_and_staff(): void
    {
        $owner = User::factory()->create();

        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $owner->stores()->attach($store->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $this->assertTrue($owner->isOwnerOf($store));
        $this->assertTrue($owner->canAccessStore($store));
        $this->assertTrue($owner->canManageStore($store));
        $this->assertTrue($owner->canManageStaffAt($store));
    }

    public function test_employee_can_manage_store_but_not_staff(): void
    {
        $employee = User::factory()->create();

        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $employee->stores()->attach($store->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $this->assertTrue($employee->isEmployeeOf($store));
        $this->assertTrue($employee->canAccessStore($store));
        $this->assertTrue($employee->canManageStore($store));
        $this->assertFalse($employee->canManageStaffAt($store));
    }

    public function test_customer_cannot_access_store_backoffice(): void
    {
        $customer = User::factory()->create();

        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $this->assertFalse($customer->isAdmin());
        $this->assertFalse($customer->canAccessStore($store));
        $this->assertFalse($customer->canManageStore($store));
        $this->assertFalse($customer->canManageStaffAt($store));
    }

    public function test_user_can_have_different_roles_in_different_stores(): void
    {
        $user = User::factory()->create();

        $ciney = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $dinant = Store::create([
            'name' => 'Boulangerie Dinant',
            'slug' => 'boulangerie-dinant',
            'city' => 'Dinant',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $user->stores()->attach([
            $ciney->id => [
                'role' => StoreRole::OWNER->value,
            ],
            $dinant->id => [
                'role' => StoreRole::EMPLOYEE->value,
            ],
        ]);

        $this->assertTrue($user->isOwnerOf($ciney));
        $this->assertFalse($user->isEmployeeOf($ciney));

        $this->assertTrue($user->isEmployeeOf($dinant));
        $this->assertFalse($user->isOwnerOf($dinant));

        $this->assertTrue($user->canManageStaffAt($ciney));
        $this->assertFalse($user->canManageStaffAt($dinant));
    }
}