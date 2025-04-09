<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statut extends Model
{
    protected $fillable = [
        'nom',
    ];

    public function profils()
    {
        return $this->hasMany(Profil::class);
    }
}
