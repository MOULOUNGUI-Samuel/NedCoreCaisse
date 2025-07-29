<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategorieMotif extends Model
{
    use HasFactory;
    
    // Pour forcer le nom de la table car Laravel pourrait chercher "categorie_motifs"
    protected $table = 'categories_motifs';

    protected $fillable = [
        'nom_categorie',
        'type_operation',
    ];

    public function motifsStandards(): HasMany
    {
        return $this->hasMany(MotifStandard::class, 'categorie_motif_id');
    }
}