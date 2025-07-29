<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotifStandard extends Model
{
    use HasFactory;

    // Pour forcer le nom de la table
    protected $table = 'motifs_standards';

    protected $fillable = [
        'categorie_motif_id',
        'libelle_motif',
        'est_special_autre',
        'est_actif',
    ];

    protected $casts = [
        'est_special_autre' => 'boolean',
        'est_actif' => 'boolean',
    ];

    public function categorieMotif(): BelongsTo
    {
        return $this->belongsTo(CategorieMotif::class, 'categorie_motif_id');
    }
}