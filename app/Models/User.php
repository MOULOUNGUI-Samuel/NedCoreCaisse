<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Votre trait pour les UUIDs
use Laravel\Sanctum\HasApiTokens; // Le trait essentiel pour Sanctum
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable
{
    /**
     * The model's traits.
     * C'est la bonne pratique de regrouper tous les traits sur une seule ligne.
     */
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nedcore_user_id',
        'societe_id',
        'code_entreprise', 
        'photo',
        'role',
        'username',
        'email',
        'identifiant',
        'google_id',
        'facebook_id',
        'password',
        'super_admin',
        'tout_voir',
        'caisse_nedco',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     * La syntaxe avec une méthode casts() est la nouvelle norme depuis Laravel 9/10,
     * c'est donc parfait que vous l'utilisiez.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // 'hashed' est la bonne pratique, Laravel s'occupe de tout.
        ];
    }
     public function societe(): BelongsTo
    {
        // CHANGÉ : La relation est maintenant standard
        return $this->belongsTo(Societe::class);
    }
     public function mouvements_operes(): HasMany
    {
        return $this->hasMany(Mouvement::class, 'operateur_id');
    }

    public function mouvements_annules(): HasMany
    {
        return $this->hasMany(Mouvement::class, 'annulateur_id');
    }
}