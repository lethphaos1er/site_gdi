<?php

namespace Tests\Feature;

use App\Enums\StoreRole;
use App\Http\Controllers\Backoffice\StoreStaffController;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class StoreStaffControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')->get(
            '/test/backoffice/stores/{store}/staff',
            [StoreStaffController::class, 'index']
        );

        Route::middleware('web')->post(
            '/test/backoffice/stores/{store}/staff',
            [StoreStaffController::class, 'store']
        );

        Route::middleware('web')->put(
            '/test/backoffice/stores/{store}/staff/{user}',
            [StoreStaffController::class, 'update']
        );

        Route::middleware('web')->delete(
            '/test/backoffice/stores/{store}/staff/{user}',
            [StoreStaffController::class, 'destroy']
        );
    }

    public function test_index_returns_store_staff_ordered_by_name(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $admin = User::factory()->create();

        $admin->forceFill([
            'is_admin' => true,
        ])->save();

        $this->actingAs($admin);

        $owner = User::factory()->create([
            'name' => 'Alice Patron',
            'email' => 'alice@example.com',
        ]);

        $employee = User::factory()->create([
            'name' => 'Bernard Employé',
            'email' => 'bernard@example.com',
        ]);

        User::factory()->create([
            'name' => 'Client Sans Rôle',
            'email' => 'client@example.com',
        ]);

        $store->users()->attach($employee->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $store->users()->attach($owner->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $response = $this->get(
            "/test/backoffice/stores/{$store->id}/staff"
        );

        $response
            ->assertOk()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Backoffice/StoreStaff')
                    ->where('store.id', $store->id)
                    ->where('store.name', 'Boulangerie Ciney')
                    ->has('staff', 2)
                    ->where('staff.0.id', $owner->id)
                    ->where('staff.0.name', 'Alice Patron')
                    ->where('staff.0.email', 'alice@example.com')
                    ->where('staff.0.role', StoreRole::OWNER->value)
                    ->where('staff.1.id', $employee->id)
                    ->where('staff.1.name', 'Bernard Employé')
                    ->where('staff.1.email', 'bernard@example.com')
                    ->where('staff.1.role', StoreRole::EMPLOYEE->value)
                    ->missing('staff.2')
            );
    }

    public function test_store_assigns_a_role_to_a_user(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $admin = User::factory()->create();

        $admin->forceFill([
            'is_admin' => true,
        ])->save();

        $this->actingAs($admin);

        $user = User::factory()->create();

        $response = $this->from(
            "/test/backoffice/stores/{$store->id}/staff"
        )->post(
            "/test/backoffice/stores/{$store->id}/staff",
            [
                'user_id' => $user->id,
                'role' => StoreRole::EMPLOYEE->value,
            ]
        );

        $response
            ->assertRedirect(
                "/test/backoffice/stores/{$store->id}/staff"
            )
            ->assertSessionHas(
                'success',
                'Le membre du personnel a été ajouté.'
            );

        $this->assertDatabaseHas('store_user', [
            'store_id' => $store->id,
            'user_id' => $user->id,
            'role' => StoreRole::EMPLOYEE->value,
        ]);
    }

    public function test_store_rejects_an_invalid_role(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $user = User::factory()->create();

        $response = $this->from(
            "/test/backoffice/stores/{$store->id}/staff"
        )->post(
            "/test/backoffice/stores/{$store->id}/staff",
            [
                'user_id' => $user->id,
                'role' => 'manager',
            ]
        );

        $response
            ->assertRedirect(
                "/test/backoffice/stores/{$store->id}/staff"
            )
            ->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('store_user', [
            'store_id' => $store->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_store_rejects_an_unknown_user(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $response = $this->from(
            "/test/backoffice/stores/{$store->id}/staff"
        )->post(
            "/test/backoffice/stores/{$store->id}/staff",
            [
                'user_id' => 999999,
                'role' => StoreRole::EMPLOYEE->value,
            ]
        );

        $response
            ->assertRedirect(
                "/test/backoffice/stores/{$store->id}/staff"
            )
            ->assertSessionHasErrors('user_id');
    }

    public function test_store_rejects_a_user_already_attached_to_store(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $user = User::factory()->create();

        $store->users()->attach($user->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $response = $this->from(
            "/test/backoffice/stores/{$store->id}/staff"
        )->post(
            "/test/backoffice/stores/{$store->id}/staff",
            [
                'user_id' => $user->id,
                'role' => StoreRole::OWNER->value,
            ]
        );

        $response
            ->assertRedirect(
                "/test/backoffice/stores/{$store->id}/staff"
            )
            ->assertSessionHasErrors('user_id');

        $this->assertDatabaseHas('store_user', [
            'store_id' => $store->id,
            'user_id' => $user->id,
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $this->assertDatabaseCount('store_user', 1);
    }

    public function test_update_changes_employee_role_to_owner(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $admin = User::factory()->create();

        $admin->forceFill([
            'is_admin' => true,
        ])->save();

        $this->actingAs($admin);

        $user = User::factory()->create();

        $store->users()->attach($user->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $response = $this->from(
            "/test/backoffice/stores/{$store->id}/staff"
        )->put(
            "/test/backoffice/stores/{$store->id}/staff/{$user->id}",
            [
                'role' => StoreRole::OWNER->value,
            ]
        );

        $response
            ->assertRedirect(
                "/test/backoffice/stores/{$store->id}/staff"
            )
            ->assertSessionHas(
                'success',
                'Le rôle du membre du personnel a été modifié.'
            );

        $this->assertDatabaseHas('store_user', [
            'store_id' => $store->id,
            'user_id' => $user->id,
            'role' => StoreRole::OWNER->value,
        ]);
    }

    public function test_update_rejects_an_invalid_role(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $user = User::factory()->create();

        $store->users()->attach($user->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $response = $this->from(
            "/test/backoffice/stores/{$store->id}/staff"
        )->put(
            "/test/backoffice/stores/{$store->id}/staff/{$user->id}",
            [
                'role' => 'manager',
            ]
        );

        $response
            ->assertRedirect(
                "/test/backoffice/stores/{$store->id}/staff"
            )
            ->assertSessionHasErrors('role');

        $this->assertDatabaseHas('store_user', [
            'store_id' => $store->id,
            'user_id' => $user->id,
            'role' => StoreRole::EMPLOYEE->value,
        ]);
    }

    public function test_update_returns_404_when_user_is_not_attached_to_store(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $user = User::factory()->create();

        $response = $this->put(
            "/test/backoffice/stores/{$store->id}/staff/{$user->id}",
            [
                'role' => StoreRole::OWNER->value,
            ]
        );

        $response->assertNotFound();

        $this->assertDatabaseMissing('store_user', [
            'store_id' => $store->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_destroy_removes_user_from_store(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $admin = User::factory()->create();

        $admin->forceFill([
            'is_admin' => true,
        ])->save();

        $this->actingAs($admin);

        $user = User::factory()->create();

        $store->users()->attach($user->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $response = $this->from(
            "/test/backoffice/stores/{$store->id}/staff"
        )->delete(
            "/test/backoffice/stores/{$store->id}/staff/{$user->id}"
        );

        $response
            ->assertRedirect(
                "/test/backoffice/stores/{$store->id}/staff"
            )
            ->assertSessionHas(
                'success',
                'Le membre du personnel a été retiré.'
            );

        $this->assertDatabaseMissing('store_user', [
            'store_id' => $store->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
    }

    public function test_destroy_returns_404_when_user_is_not_attached_to_store(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $user = User::factory()->create();

        $response = $this->delete(
            "/test/backoffice/stores/{$store->id}/staff/{$user->id}"
        );

        $response->assertNotFound();

        $this->assertDatabaseMissing('store_user', [
            'store_id' => $store->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
    }
    public function test_owner_can_view_store_staff(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $owner = User::factory()->create();

        $store->users()->attach($owner->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $this->actingAs($owner);

        $this->get(
            "/test/backoffice/stores/{$store->id}/staff"
        )->assertOk();
    }

    public function test_employee_cannot_view_store_staff(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $employee = User::factory()->create();

        $store->users()->attach($employee->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $this->actingAs($employee);

        $this->get(
            "/test/backoffice/stores/{$store->id}/staff"
        )->assertForbidden();
    }

    public function test_owner_can_add_an_employee(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $owner = User::factory()->create();

        $store->users()->attach($owner->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $user = User::factory()->create();

        $this->actingAs($owner);

        $this->post(
            "/test/backoffice/stores/{$store->id}/staff",
            [
                'user_id' => $user->id,
                'role' => StoreRole::EMPLOYEE->value,
            ]
        )->assertRedirect();

        $this->assertDatabaseHas('store_user', [
            'store_id' => $store->id,
            'user_id' => $user->id,
            'role' => StoreRole::EMPLOYEE->value,
        ]);
    }

    public function test_owner_cannot_add_another_owner(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $owner = User::factory()->create();

        $store->users()->attach($owner->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $user = User::factory()->create();

        $this->actingAs($owner);

        $response = $this->from(
            "/test/backoffice/stores/{$store->id}/staff"
        )->post(
            "/test/backoffice/stores/{$store->id}/staff",
            [
                'user_id' => $user->id,
                'role' => StoreRole::OWNER->value,
            ]
        );

        $response
            ->assertRedirect(
                "/test/backoffice/stores/{$store->id}/staff"
            )
            ->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('store_user', [
            'store_id' => $store->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_owner_cannot_change_employee_role(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $owner = User::factory()->create();
        $employee = User::factory()->create();

        $store->users()->attach($owner->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $store->users()->attach($employee->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $this->actingAs($owner);

        $this->put(
            "/test/backoffice/stores/{$store->id}/staff/{$employee->id}",
            [
                'role' => StoreRole::OWNER->value,
            ]
        )->assertForbidden();

        $this->assertDatabaseHas('store_user', [
            'store_id' => $store->id,
            'user_id' => $employee->id,
            'role' => StoreRole::EMPLOYEE->value,
        ]);
    }

    public function test_owner_can_remove_an_employee(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $owner = User::factory()->create();
        $employee = User::factory()->create();

        $store->users()->attach($owner->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $store->users()->attach($employee->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $this->actingAs($owner);

        $this->delete(
            "/test/backoffice/stores/{$store->id}/staff/{$employee->id}"
        )->assertRedirect();

        $this->assertDatabaseMissing('store_user', [
            'store_id' => $store->id,
            'user_id' => $employee->id,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $employee->id,
        ]);
    }

    public function test_owner_cannot_remove_another_owner(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $owner = User::factory()->create();
        $otherOwner = User::factory()->create();

        $store->users()->attach($owner->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $store->users()->attach($otherOwner->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $this->actingAs($owner);

        $this->delete(
            "/test/backoffice/stores/{$store->id}/staff/{$otherOwner->id}"
        )->assertForbidden();

        $this->assertDatabaseHas('store_user', [
            'store_id' => $store->id,
            'user_id' => $otherOwner->id,
            'role' => StoreRole::OWNER->value,
        ]);
    }
    public function test_admin_cannot_demote_last_owner(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $admin = User::factory()->create();

        $admin->forceFill([
            'is_admin' => true,
        ])->save();

        $owner = User::factory()->create();

        $store->users()->attach($owner->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $this->actingAs($admin);

        $response = $this->from(
            "/test/backoffice/stores/{$store->id}/staff"
        )->put(
            "/test/backoffice/stores/{$store->id}/staff/{$owner->id}",
            [
                'role' => StoreRole::EMPLOYEE->value,
            ]
        );

        $response
            ->assertRedirect(
                "/test/backoffice/stores/{$store->id}/staff"
            )
            ->assertSessionHasErrors('role');

        $this->assertDatabaseHas('store_user', [
            'store_id' => $store->id,
            'user_id' => $owner->id,
            'role' => StoreRole::OWNER->value,
        ]);
    }

    public function test_admin_cannot_remove_last_owner(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $admin = User::factory()->create();

        $admin->forceFill([
            'is_admin' => true,
        ])->save();

        $owner = User::factory()->create();

        $store->users()->attach($owner->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $this->actingAs($admin);

        $response = $this->from(
            "/test/backoffice/stores/{$store->id}/staff"
        )->delete(
            "/test/backoffice/stores/{$store->id}/staff/{$owner->id}"
        );

        $response
            ->assertRedirect(
                "/test/backoffice/stores/{$store->id}/staff"
            )
            ->assertSessionHasErrors('user');

        $this->assertDatabaseHas('store_user', [
            'store_id' => $store->id,
            'user_id' => $owner->id,
            'role' => StoreRole::OWNER->value,
        ]);
    }

    public function test_admin_can_demote_owner_when_another_owner_remains(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $admin = User::factory()->create();

        $admin->forceFill([
            'is_admin' => true,
        ])->save();

        $firstOwner = User::factory()->create();
        $secondOwner = User::factory()->create();

        $store->users()->attach($firstOwner->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $store->users()->attach($secondOwner->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $this->actingAs($admin);

        $this->put(
            "/test/backoffice/stores/{$store->id}/staff/{$firstOwner->id}",
            [
                'role' => StoreRole::EMPLOYEE->value,
            ]
        )->assertRedirect();

        $this->assertDatabaseHas('store_user', [
            'store_id' => $store->id,
            'user_id' => $firstOwner->id,
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $this->assertDatabaseHas('store_user', [
            'store_id' => $store->id,
            'user_id' => $secondOwner->id,
            'role' => StoreRole::OWNER->value,
        ]);
    }

    public function test_admin_can_remove_owner_when_another_owner_remains(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $admin = User::factory()->create();

        $admin->forceFill([
            'is_admin' => true,
        ])->save();

        $firstOwner = User::factory()->create();
        $secondOwner = User::factory()->create();

        $store->users()->attach($firstOwner->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $store->users()->attach($secondOwner->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        $this->actingAs($admin);

        $this->delete(
            "/test/backoffice/stores/{$store->id}/staff/{$firstOwner->id}"
        )->assertRedirect();

        $this->assertDatabaseMissing('store_user', [
            'store_id' => $store->id,
            'user_id' => $firstOwner->id,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $firstOwner->id,
        ]);

        $this->assertDatabaseHas('store_user', [
            'store_id' => $store->id,
            'user_id' => $secondOwner->id,
            'role' => StoreRole::OWNER->value,
        ]);
    }

    public function test_employee_cannot_manage_staff(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $employee = User::factory()->create();
        $user = User::factory()->create();

        $store->users()->attach($employee->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $this->actingAs($employee);

        $this->get(
            "/test/backoffice/stores/{$store->id}/staff"
        )->assertForbidden();

        $this->post(
            "/test/backoffice/stores/{$store->id}/staff",
            [
                'user_id' => $user->id,
                'role' => StoreRole::EMPLOYEE->value,
            ]
        )->assertForbidden();

        $this->assertDatabaseMissing('store_user', [
            'store_id' => $store->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_customer_cannot_manage_staff(): void
    {
        $store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);

        $customer = User::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($customer);

        $this->get(
            "/test/backoffice/stores/{$store->id}/staff"
        )->assertForbidden();

        $this->post(
            "/test/backoffice/stores/{$store->id}/staff",
            [
                'user_id' => $user->id,
                'role' => StoreRole::EMPLOYEE->value,
            ]
        )->assertForbidden();

        $this->assertDatabaseMissing('store_user', [
            'store_id' => $store->id,
            'user_id' => $user->id,
        ]);
    }
}
