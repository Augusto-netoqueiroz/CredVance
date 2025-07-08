<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->timestamp('enviado_em')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropColumn('enviado_em');
        });
    }
};
