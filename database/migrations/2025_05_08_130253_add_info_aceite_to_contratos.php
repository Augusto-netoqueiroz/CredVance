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
        Schema::table('contratos', function (Blueprint $table) {
            $table->timestamp('aceito_em')->nullable();
            $table->text('navegador_info')->nullable();
            $table->string('resolucao')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('pdf_contrato')->nullable();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contratos', function (Blueprint $table) {
            //
        });
    }
};
