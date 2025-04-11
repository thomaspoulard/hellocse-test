<?php

namespace Database\Seeders;

use App\Models\Statut;
use Illuminate\Database\Seeder;

class StatutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $statuts = ['inactif', 'en_attente', 'actif'];

        foreach ($statuts as $nom) {
            Statut::firstOrCreate(['nom' => $nom]);
        }
    }
}
