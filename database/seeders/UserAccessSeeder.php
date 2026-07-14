<?php

namespace Database\Seeders;

use App\Enums\StoreRole;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAccessSeeder extends Seeder
{
    public function run(): void
    {
        $ciney = Store::where('slug', 'boulangerie-ciney')->firstOrFail();
        $dinant = Store::where('slug', 'boulangerie-dinant')->firstOrFail();

        $admin = User::updateOrCreate(
            ['email' => 'admin@test.be'],
            [
                'name' => 'Administrateur Test',
                'password' => Hash::make('password'),
            ],
        );

        $admin->forceFill([
            'is_admin' => true,
        ])->save();

        $admin->stores()->detach();

        $owner = User::updateOrCreate(
            ['email' => 'owner@test.be'],
            [
                'name' => 'Patron Test',
                'password' => Hash::make('password'),
            ],
        );

        $owner->forceFill([
            'is_admin' => false,
        ])->save();

        $owner->stores()->sync([
            $ciney->id => [
                'role' => StoreRole::OWNER->value,
            ],
        ]);

        $employee = User::updateOrCreate(
            ['email' => 'employee@test.be'],
            [
                'name' => 'Employé Test',
                'password' => Hash::make('password'),
            ],
        );

        $employee->forceFill([
            'is_admin' => false,
        ])->save();

        $employee->stores()->sync([
            $ciney->id => [
                'role' => StoreRole::EMPLOYEE->value,
            ],
            $dinant->id => [
                'role' => StoreRole::EMPLOYEE->value,
            ],
        ]);

        $customer = User::updateOrCreate(
            ['email' => 'customer@test.be'],
            [
                'name' => 'Client Test',
                'password' => Hash::make('password'),
            ],
        );

        $customer->forceFill([
            'is_admin' => false,
        ])->save();

        $customer->stores()->detach();
    }
}