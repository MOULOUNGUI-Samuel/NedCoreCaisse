<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategorieMotif extends Model
{
    use HasFactory;

    protected $table = 'categories_motifs';
    public $incrementing = false; // Désactive l'auto-incrément
    protected $keyType = 'string'; // UUID = string

    protected $fillable = [
        'nom_categorie',
        'societe_id',
        'type_operation',
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

    public function societe(): BelongsTo
    {
        // CHANGÉ : La relation est maintenant standard
        return $this->belongsTo(Societe::class);
    }
    public function motifsStandards(): HasMany
    {
        return $this->hasMany(MotifStandard::class, 'categorie_motif_id');
    }


}
