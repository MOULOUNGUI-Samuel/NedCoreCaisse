<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocieteUserTable extends Migration
{
    public function up()
    {
        Schema::create('societe_user', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id');
            $table->uuid('societe_id');

            $table->string('role')->nullable(); // Exemple : 'admin', 'agent', 'visiteur'
            $table->boolean('est_actif')->default(true);
            $table->timestamp('associe_le')->useCurrent();

            $table->timestamps();

            // Index et contraintes
            $table->unique(['user_id', 'societe_id']);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('societe_id')->references('id')->on('societes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('societe_user');
    }
}

