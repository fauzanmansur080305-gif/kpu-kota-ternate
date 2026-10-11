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
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin@kpu-ternate.go.id'],
            ['name' => 'Administrator', 'nip' => '197001012000011001', 'password' => 'password', 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'anggota@kpu-ternate.go.id'],
            ['name' => 'Anggota KPU', 'nip' => '198001012005011002', 'password' => 'password', 'role' => 'anggota']
        );
    }
}
