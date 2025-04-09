<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    /** @use HasFactory<\Database\Factories\ProfilFactory> */
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'image',
        'statut_id',
    ];

    public function statut()
    {
        return $this->belongsTo(Statut::class);
    }
}
