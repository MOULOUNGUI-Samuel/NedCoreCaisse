<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motifs_standards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Clé étrangère vers 'categories_motifs'
            
             $table->foreignUuid('categorie_motif_id')->constrained('categories_motifs')->onDelete('restrict');
            $table->string('libelle_motif');
            $table->boolean('est_special_autre')->default(false);
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motifs_standards');
    }
};