<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Caisse extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['libelle_caisse', 'user_id', 'societe_id', 'seuil_encaissement',
        'seuil_maximum', 
     'decouvert_autorise', 'est_supprime']; // CHANGÉ

    protected $casts = ['decouvert_autorise' => 'boolean', 'est_supprime' => 'boolean'];

    public function societe(): BelongsTo
    {
        // CHANGÉ : La relation est maintenant standard
        return $this->belongsTo(Societe::class);
    }
    public function user(): BelongsTo
    {
        // CHANGÉ : La relation est maintenant standard
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mouvements(): HasMany
    {
        return $this->hasMany(Mouvement::class, 'caisse_id');
    }
}