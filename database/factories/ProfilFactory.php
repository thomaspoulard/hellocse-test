<?php

namespace Database\Factories;

use App\Models\Statut;
use App\Models\Profil;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profil>
 */
class ProfilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->name(),
            'prenom' => fake()->firstName(),
            'statut_id' => fake()->numberBetween(Statut::first()->id, Statut::orderBy('id', 'desc')->first()->id),
            'created_at' => now(),
            'updated_at' => now(),
            'image' => ''
        ];
    }
}
