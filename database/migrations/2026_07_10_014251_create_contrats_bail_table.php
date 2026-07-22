<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contrats_bail', function (Blueprint $table) {
            $table->id();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->decimal('loyer_mensuel', 10, 2);
            $table->decimal('caution', 10, 2);
            $table->string('statut')->default('actif');
            $table->string('fichier_pdf')->nullable();
            $table->foreignId('bien_id')->constrained('biens_immobiliers');
            $table->foreignId('locataire_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contrats_bail');
    }
};
