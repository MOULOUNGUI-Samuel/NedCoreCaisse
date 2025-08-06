<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class SocieteUser extends Model
{
    use HasFactory;

    // Nom de la table (pivot personnalisée)
    protected $table = 'societe_user';

    // UUID comme clé primaire
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'societe_id',
        'role',
        'est_actif',
        'associe_le',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }
}
