<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // create 10 random users
        User::factory(10)->create();

        // make a specific test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'uuid' => Uuid::uuid4()->toString(),
            'password' => Hash::make('password'),
        ]);
    }
}
