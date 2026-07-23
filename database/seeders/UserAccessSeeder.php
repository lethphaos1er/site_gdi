<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAccessSeeder extends Seeder
{
    public function run(): void
    {
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
    }
}