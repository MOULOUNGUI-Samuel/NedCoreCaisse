<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('societes', function (Blueprint $table) {
             $table->uuid('id')->primary();
            // Le code_societe est conservé comme un identifiant métier unique et lisible.
            $table->string('nom_societe');
            $table->string('code_societe')->unique();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
             $table->string('logo')->nullable();
            $table->string('adresse')->nullable();
             $table->boolean('statut')->default(1);
            $table->timestamps(); // Ajoute created_at et updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('societes');
    }
};