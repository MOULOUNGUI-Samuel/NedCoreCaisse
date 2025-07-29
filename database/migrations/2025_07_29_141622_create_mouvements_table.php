<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('num_mouvement')->unique();
            
            $table->foreignUuid('caisse_id')->constrained('caisses')->onDelete('restrict');
            
            // CHANGÉ : La clé étrangère est maintenant de type UUID.
            $table->foreignUuid('motif_standard_id')->constrained('motifs_standards')->onDelete('restrict');
            
            $table->foreignUuid('operateur_id')->constrained('users', 'id')->onDelete('restrict');
            $table->foreignUuid('annulateur_id')->nullable()->constrained('users', 'id')->onDelete('set null');

            $table->dateTime('date_mouvement');
            $table->string('libelle_personnalise')->nullable();
            $table->decimal('montant_debit', 15, 2)->default(0.00);
            $table->decimal('montant_credit', 15, 2)->default(0.00);
            $table->decimal('solde_apres_mouvement', 15, 2);
            $table->string('mode_reglement')->nullable();
            $table->string('reference_externe')->nullable();
            $table->text('observations')->nullable();
            
            $table->boolean('est_annule')->default(false);
            $table->dateTime('date_annulation')->nullable();
            $table->string('motif_annulation')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements');
    }
};