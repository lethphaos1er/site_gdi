<?php

namespace Tests\Feature;

use App\Enums\StoreRole;
use App\Models\Store;
use App\Models\User;
use App\Policies\StorePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Gate;

class StorePolicyTest extends TestCase
{
    use RefreshDatabase;

    private StorePolicy $policy;
    private Store $store;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = new StorePolicy();

        $this->store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);
    }

    public function test_admin_is_authorized_before_every_policy_check(): void
    {
        $admin = User::factory()->create();

        $admin->forceFill([
            'is_admin' => true,
        ])->save();

        $this->assertTrue($this->policy->before($admin));
    }

    public function test_owner_can_view_and_manage_store_and_staff(): void
    {
        $owner = User::factory()->create();

        $owner->stores()->attach($this->store->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $this->assertTrue($this->policy->viewAny($owner));
        $this->assertTrue($this->policy->view($owner, $this->store));
        $this->assertTrue($this->policy->manage($owner, $this->store));
        $this->assertTrue($this->policy->manageStaff($owner, $this->store));
    }

    public function test_employee_can_view_and_manage_store_but_not_staff(): void
    {
        $employee = User::factory()->create();

        $employee->stores()->attach($this->store->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $this->assertTrue($this->policy->viewAny($employee));
        $this->assertTrue($this->policy->view($employee, $this->store));
        $this->assertTrue($this->policy->manage($employee, $this->store));
        $this->assertFalse(
            $this->policy->manageStaff($employee, $this->store)
        );
    }

    public function test_customer_cannot_access_store(): void
    {
        $customer = User::factory()->create();

        $this->assertFalse($this->policy->viewAny($customer));
        $this->assertFalse($this->policy->view($customer, $this->store));
        $this->assertFalse($this->policy->manage($customer, $this->store));
        $this->assertFalse(
            $this->policy->manageStaff($customer, $this->store)
        );
    }

    public function test_user_cannot_access_an_unassigned_store(): void
    {
        $employee = User::factory()->create();

        $assignedStore = Store::create([
            'name' => 'Boulangerie Dinant',
            'slug' => 'boulangerie-dinant',
            'city' => 'Dinant',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $employee->stores()->attach($assignedStore->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $this->assertTrue($this->policy->viewAny($employee));
        $this->assertFalse($this->policy->view($employee, $this->store));
        $this->assertFalse($this->policy->manage($employee, $this->store));
        $this->assertFalse(
            $this->policy->manageStaff($employee, $this->store)
        );
    }

    public function test_laravel_resolves_store_policy_authorizations(): void
    {
        $owner = User::factory()->create();

        $owner->stores()->attach($this->store->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $employee = User::factory()->create();

        $employee->stores()->attach($this->store->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $customer = User::factory()->create();

        $this->assertTrue(
            Gate::forUser($owner)->allows('manageStaff', $this->store)
        );

        $this->assertFalse(
            Gate::forUser($employee)->allows('manageStaff', $this->store)
        );

        $this->assertFalse(
            Gate::forUser($customer)->allows('view', $this->store)
        );
    }
    public function test_owner_can_create_employee(): void
    {
        $owner = User::factory()->create();

        $owner->stores()->attach($this->store->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $this->assertTrue(
            $this->policy->createEmployee($owner, $this->store)
        );
    }

    public function test_employee_cannot_create_employee(): void
    {
        $employee = User::factory()->create();

        $employee->stores()->attach($this->store->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $this->assertFalse(
            $this->policy->createEmployee($employee, $this->store)
        );
    }

    public function test_owner_cannot_update_staff_role(): void
    {
        $owner = User::factory()->create();

        $owner->stores()->attach($this->store->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $employee = User::factory()->create();

        $employee->stores()->attach($this->store->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $this->assertFalse(
            $this->policy->updateStaffRole(
                $owner,
                $this->store,
                $employee
            )
        );
    }

    public function test_owner_can_remove_employee(): void
    {
        $owner = User::factory()->create();

        $owner->stores()->attach($this->store->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $employee = User::factory()->create();

        $employee->stores()->attach($this->store->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $this->assertTrue(
            $this->policy->deleteStaff(
                $owner,
                $this->store,
                $employee
            )
        );
    }

    public function test_owner_cannot_remove_another_owner(): void
    {
        $owner = User::factory()->create();

        $owner->stores()->attach($this->store->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $otherOwner = User::factory()->create();

        $otherOwner->stores()->attach($this->store->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $this->assertFalse(
            $this->policy->deleteStaff(
                $owner,
                $this->store,
                $otherOwner
            )
        );
    }

    public function test_owner_cannot_remove_himself(): void
    {
        $owner = User::factory()->create();

        $owner->stores()->attach($this->store->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $this->assertFalse(
            $this->policy->deleteStaff(
                $owner,
                $this->store,
                $owner
            )
        );
    }
    public function test_admin_is_authorized_for_all_staff_actions_through_gate(): void
    {
        $admin = User::factory()->create();

        $admin->forceFill([
            'is_admin' => true,
        ])->save();

        $staffMember = User::factory()->create();

        $staffMember->stores()->attach($this->store->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $this->assertTrue(
            Gate::forUser($admin)->allows(
                'createEmployee',
                $this->store
            )
        );

        $this->assertTrue(
            Gate::forUser($admin)->allows(
                'updateStaffRole',
                [$this->store, $staffMember]
            )
        );

        $this->assertTrue(
            Gate::forUser($admin)->allows(
                'deleteStaff',
                [$this->store, $staffMember]
            )
        );
    }
}
