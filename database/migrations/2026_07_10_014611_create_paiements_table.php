<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->decimal('montant', 10, 2);
            $table->date('date_paiement');
            $table->string('mode_paiement');
            $table->string('statut')->default('payé');
            $table->string('quittance_pdf')->nullable();
            $table->foreignId('contrat_id')->constrained('contrats_bail');
            $table->foreignId('locataire_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements');
    }
};
