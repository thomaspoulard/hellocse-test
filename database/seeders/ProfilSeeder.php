<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profil;

class ProfilSeeder extends Seeder
{
    public function run()
    {
        Profil::create([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'image' => '',
            'statut_id' => 3, // Actif
        ]);

        Profil::create([
            'nom' => 'Dupont',
            'prenom' => 'Martine',
            'image' => '',
            'statut_id' => 3, // Actif
        ]);

        Profil::create([
            'nom' => 'Durand',
            'prenom' => 'Paul',
            'image' => '',
            'statut_id' => 2, // En attente
        ]);

        Profil::create([
            'nom' => 'Duront',
            'prenom' => 'Thomas',
            'image' => '',
            'statut_id' => 1, // Inactif
        ]);

        Profil::create([
            'nom' => 'Duront',
            'prenom' => 'Axel',
            'image' => '',
            'statut_id' => 1, // Inactif
        ]);
    }
}
