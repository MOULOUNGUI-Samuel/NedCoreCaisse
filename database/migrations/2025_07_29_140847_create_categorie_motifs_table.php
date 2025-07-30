<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories_motifs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nom_categorie');
            $table->foreignUuid('societe_id')->constrained('societes')->onDelete('restrict');
            $table->boolean('est_actif')->default(true);
            $table->enum('type_operation', ['Entrée', 'Sortie']);
            $table->timestamps();

            // Contrainte d'unicité
            $table->unique(['nom_categorie', 'type_operation']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories_motifs');
    }
};