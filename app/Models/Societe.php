<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids; // Important
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Societe extends Model
{
    use HasFactory, HasUuids; // CHANGÉ

    protected $fillable = [
        'code_societe',
        'nom_societe',
        'logo',
        'email',
        'telephone',
        'statut',
        'logo',
        'adresse'
    ];

    public function personnels(): HasMany
    {
        // CHANGÉ : La relation est maintenant standard
        return $this->hasMany(User::class);
    }

    public function caisses(): HasMany
    {
        // CHANGÉ : La relation est maintenant standard
        return $this->hasMany(Caisse::class);
    }
}
