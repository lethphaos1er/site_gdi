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

        $owner = User::factory()->create([
            'name' => 'Alice Patron',
            'email' => 'alice@example.com',
        ]);

        $employee = User::factory()->create([
            'name' => 'Bernard Employé',
            'email' => 'bernard@example.com',
        ]);

        $customer = User::factory()->create([
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
}
