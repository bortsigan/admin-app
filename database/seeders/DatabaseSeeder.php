<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Interest;
use App\Models\ClientInterest;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Client']);

        # Create two admin users
        User::factory()->admin()->create([
            'email' => 'admin1@example.com',
            'first_name' => 'Admin',
            'last_name' => 'User1',
            'contact_no' => '1234567890',
            'birthday' => '1990-01-01',
            'user_id' => null,
            'password' => Hash::make('password'),
        ]);

        User::factory()->admin()->create([
            'email' => 'admin2@example.com',
            'first_name' => 'Admin',
            'last_name' => 'User2',
            'contact_no' => '0987654321',
            'birthday' => '1990-02-01',
            'user_id' => null,
            'password' => Hash::make('password'),
        ]);

        User::factory()->count(10)->create();
        Interest::factory()->count(10)->create();
        ClientInterest::factory()->count(10)->create();
    }
}
