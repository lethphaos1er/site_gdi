<?php

namespace Tests\Feature;

use App\Enums\StoreRole;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreStaffSearchControllerTest extends TestCase
{
    use RefreshDatabase;

    private Store $store;

    protected function setUp(): void
    {
        parent::setUp();

        $this->store = Store::create([
            'name' => 'Boulangerie Ciney',
            'slug' => 'boulangerie-ciney',
            'city' => 'Ciney',
            'type' => 'bakery',
            'api_base_url' => null,
        ]);
    }

    public function test_owner_can_search_user_by_first_name(): void
    {
        $owner = $this->createOwner();

        $expectedUser = User::factory()->create([
            'name' => 'Alice Dupont',
            'first_name' => 'Alice',
            'last_name' => 'Dupont',
            'email' => 'alice@example.com',
        ]);

        User::factory()->create([
            'name' => 'Bernard Martin',
            'first_name' => 'Bernard',
            'last_name' => 'Martin',
            'email' => 'bernard@example.com',
        ]);

        $this->actingAs($owner);

        $response = $this->getJson(
            $this->searchUrl('Alice')
        );

        $response
            ->assertOk()
            ->assertJsonCount(1, 'users')
            ->assertJsonPath('users.0.id', $expectedUser->id)
            ->assertJsonPath('users.0.first_name', 'Alice')
            ->assertJsonPath('users.0.last_name', 'Dupont')
            ->assertJsonPath('users.0.full_name', 'Alice Dupont')
            ->assertJsonPath('users.0.email', 'alice@example.com');
    }

    public function test_owner_can_search_user_by_last_name(): void
    {
        $owner = $this->createOwner();

        $expectedUser = User::factory()->create([
            'name' => 'Alice Dupont',
            'first_name' => 'Alice',
            'last_name' => 'Dupont',
            'email' => 'alice@example.com',
        ]);

        $this->actingAs($owner);

        $response = $this->getJson(
            $this->searchUrl('Dupont')
        );

        $response
            ->assertOk()
            ->assertJsonCount(1, 'users')
            ->assertJsonPath('users.0.id', $expectedUser->id);
    }

    public function test_owner_can_search_user_by_email(): void
    {
        $owner = $this->createOwner();

        $expectedUser = User::factory()->create([
            'name' => 'Alice Dupont',
            'first_name' => 'Alice',
            'last_name' => 'Dupont',
            'email' => 'alice.dupont@example.com',
        ]);

        $this->actingAs($owner);

        $response = $this->getJson(
            $this->searchUrl('alice.dupont@')
        );

        $response
            ->assertOk()
            ->assertJsonCount(1, 'users')
            ->assertJsonPath('users.0.id', $expectedUser->id);
    }

    public function test_owner_can_search_user_by_full_name(): void
    {
        $owner = $this->createOwner();

        $expectedUser = User::factory()->create([
            'name' => 'Alice Dupont',
            'first_name' => 'Alice',
            'last_name' => 'Dupont',
            'email' => 'alice@example.com',
        ]);

        $this->actingAs($owner);

        $response = $this->getJson(
            $this->searchUrl('Alice Dupont')
        );

        $response
            ->assertOk()
            ->assertJsonCount(1, 'users')
            ->assertJsonPath('users.0.id', $expectedUser->id);
    }

    public function test_search_excludes_users_already_attached_to_store(): void
    {
        $owner = $this->createOwner();

        $attachedUser = User::factory()->create([
            'name' => 'Alice Attachée',
            'first_name' => 'Alice',
            'last_name' => 'Attachée',
            'email' => 'alice.attached@example.com',
        ]);

        $availableUser = User::factory()->create([
            'name' => 'Alice Disponible',
            'first_name' => 'Alice',
            'last_name' => 'Disponible',
            'email' => 'alice.available@example.com',
        ]);

        $this->store->users()->attach($attachedUser->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $this->actingAs($owner);

        $response = $this->getJson(
            $this->searchUrl('Alice')
        );

        $response
            ->assertOk()
            ->assertJsonCount(1, 'users')
            ->assertJsonPath('users.0.id', $availableUser->id)
            ->assertJsonMissing([
                'id' => $attachedUser->id,
            ]);
    }

    public function test_search_is_limited_to_ten_results(): void
    {
        $owner = $this->createOwner();

        for ($index = 1; $index <= 12; $index++) {
            User::factory()->create([
                'name' => "Recherche Utilisateur {$index}",
                'first_name' => 'Recherche',
                'last_name' => "Utilisateur {$index}",
                'email' => "recherche{$index}@example.com",
            ]);
        }

        $this->actingAs($owner);

        $response = $this->getJson(
            $this->searchUrl('Recherche')
        );

        $response
            ->assertOk()
            ->assertJsonCount(10, 'users');
    }

    public function test_admin_can_search_users(): void
    {
        $admin = User::factory()->create();

        $admin->forceFill([
            'is_admin' => true,
        ])->save();

        $expectedUser = User::factory()->create([
            'name' => 'Alice Dupont',
            'first_name' => 'Alice',
            'last_name' => 'Dupont',
            'email' => 'alice@example.com',
        ]);

        $this->actingAs($admin);

        $response = $this->getJson(
            $this->searchUrl('Alice')
        );

        $response
            ->assertOk()
            ->assertJsonPath('users.0.id', $expectedUser->id);
    }

    public function test_employee_cannot_search_users(): void
    {
        $employee = User::factory()->create();

        $this->store->users()->attach($employee->id, [
            'role' => StoreRole::EMPLOYEE->value,
        ]);

        $this->actingAs($employee);

        $this->getJson(
            $this->searchUrl('Alice')
        )->assertForbidden();
    }

    public function test_customer_cannot_search_users(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer);

        $this->getJson(
            $this->searchUrl('Alice')
        )->assertForbidden();
    }

    public function test_search_requires_at_least_two_characters(): void
    {
        $owner = $this->createOwner();

        $this->actingAs($owner);

        $response = $this->getJson(
            $this->searchUrl('A')
        );

        $response
            ->assertStatus(422)
            ->assertJsonPath(
                'errors.search.0',
                'Saisissez au moins 2 caractères.'
            );
    }

    private function createOwner(): User
    {
        $owner = User::factory()->create();

        $this->store->users()->attach($owner->id, [
            'role' => StoreRole::OWNER->value,
        ]);

        return $owner;
    }

    private function searchUrl(string $search): string
    {
        return route(
            'backoffice.stores.staff.search',
            ['store' => $this->store]
        ).'?'.http_build_query([
            'search' => $search,
        ]);
    }
}