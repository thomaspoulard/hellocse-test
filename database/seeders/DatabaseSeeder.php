<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(StatutSeeder::class);
        $this->call(ProfilSeeder::class); // ProfilSeeder is called after Statut's to avoid foreign key error
    }
}
