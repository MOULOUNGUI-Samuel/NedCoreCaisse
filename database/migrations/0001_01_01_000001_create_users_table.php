<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('nedcore_user_id');
            $table->uuid('code_entreprise');
            $table->string('name')->nullable();
            // J'ai ajouté l'index unique sur username, c'est important
            $table->string('username')->unique()->nullable(); 
            $table->string('email')->unique()->nullable();
            $table->string('identifiant')->unique()->nullable();
            $table->string('google_id')->unique()->nullable();
            $table->string('password')->nullable();
            // CHANGÉ : La clé étrangère pointe maintenant vers la colonne 'id' (UUID) de la table 'societes'.
            $table->foreignUuid('societe_id')->constrained('societes')->onDelete('restrict');

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // Le champ 'id' de la session est un string, pas un UUID. C'est correct.
            
            // =======================================================
            //                  LA CORRECTION EST ICI
            // =======================================================
            // On utilise foreignUuid pour faire référence à la clé primaire UUID de la table users.
            $table->foreignUuid('user_id')->nullable()->index(); 
            
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};