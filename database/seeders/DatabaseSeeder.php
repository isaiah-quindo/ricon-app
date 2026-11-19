<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Local admin for /admin. Safe to re-run: updates the existing row.
        User::updateOrCreate(
            ['email' => 'admin@ricon.test'],
            [
                'name' => 'Ricon Admin',
                'password' => 'password',
                'role' => 'admin',
            ],
        );
    }
}
