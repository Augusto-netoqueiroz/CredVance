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
        $table->string('boleto')->nullable()->after('valor');
    });
}

public function down()
{
    Schema::table('pagamentos', function (Blueprint $table) {
        $table->dropColumn('boleto');
    });
}

};
