<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Créer un utilisateur admin de test
        User::firstOrCreate([
            'email' => 'admin@iriucbc.com'
        ], [
            'name' => 'Admin Test',
            'password' => Hash::make(env('SEED_USER_PASSWORD') ?: \Illuminate\Support\Str::random(32)),
            'email_verified_at' => now(),
        ]);

        // Créer un utilisateur modérateur de test
        User::firstOrCreate([
            'email' => 'moderator@iriucbc.com'
        ], [
            'name' => 'Modérateur Test',
            'password' => Hash::make(env('SEED_USER_PASSWORD') ?: \Illuminate\Support\Str::random(32)),
            'email_verified_at' => now(),
        ]);

        echo "Users created successfully!\n";
    }
}
