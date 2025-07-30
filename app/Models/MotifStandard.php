<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotifStandard extends Model
{
    use HasFactory;

    protected $table = 'motifs_standards';
    public $incrementing = false;
    protected $keyType = 'string';

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function categorieMotif(): BelongsTo
    {
        return $this->belongsTo(CategorieMotif::class, 'categorie_motif_id');
    }
}
