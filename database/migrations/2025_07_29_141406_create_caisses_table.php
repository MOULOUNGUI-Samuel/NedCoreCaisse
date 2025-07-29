<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caisses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('libelle_caisse');

            // Clé étrangère vers 'personnels' (peut être nulle)
            $table->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('set null');
            
             // CHANGÉ : La clé étrangère pointe maintenant vers la colonne 'id' (UUID) de la table 'societes'.
            $table->foreignUuid('societe_id')->constrained('societes')->onDelete('restrict');

            $table->decimal('seuil_encaissement', 15, 2)->default(0.00);
            $table->boolean('decouvert_autorise')->default(false);
            $table->boolean('est_supprime')->default(false); // Pour le soft delete manuel
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caisses');
    }
};