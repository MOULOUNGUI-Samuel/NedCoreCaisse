<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mouvement extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'mouvements';

    protected $fillable = [
        'num_mouvement',
        'caisse_id',
        'motif_standard_id',
        'operateur_id',
        'annulateur_id',
        'date_mouvement',
        'libelle_personnalise',
        'montant_debit',
        'montant_credit',
        'solde_apres_mouvement',
        'solde_avant_mouvement',
        'mode_reglement',
        'reference_externe',
        'observations',
        'est_annule',
        'date_annulation',
        'motif_annulation',
    ];

    protected $casts = [
        'date_mouvement' => 'datetime',
        'date_annulation' => 'datetime',
        'est_annule' => 'boolean',
    ];

    public function caisse(): BelongsTo
    {
        return $this->belongsTo(Caisse::class, 'caisse_id');
    }

    public function motifStandard(): BelongsTo
    {
        return $this->belongsTo(MotifStandard::class, 'motif_standard_id');
    }

    public function operateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operateur_id');
    }

    public function annulateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'annulateur_id');
    }
}