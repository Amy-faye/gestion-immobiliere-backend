<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->string('wave_session_id')->nullable()->after('mode_paiement');
            $table->string('wave_transaction_id')->nullable()->after('wave_session_id');
        });
    }

    public function down()
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropColumn(['wave_session_id', 'wave_transaction_id']);
        });
    }
};
