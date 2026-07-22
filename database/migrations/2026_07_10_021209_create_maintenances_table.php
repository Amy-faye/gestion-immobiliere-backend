<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->text('description_travaux');
            $table->decimal('cout', 10, 2);
            $table->date('date_intervention');
            $table->string('facture_pdf')->nullable();
            $table->string('statut')->default('en_cours');
            $table->foreignId('reclamation_id')->constrained('reclamations');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenances');
    }
};
