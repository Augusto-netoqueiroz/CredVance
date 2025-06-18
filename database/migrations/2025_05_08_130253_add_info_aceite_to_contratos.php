<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contratos', function (Blueprint $table) {
            if (!Schema::hasColumn('contratos', 'aceito_em')) {
                $table->timestamp('aceito_em')->nullable();
            }
            if (!Schema::hasColumn('contratos', 'navegador_info')) {
                $table->text('navegador_info')->nullable();
            }
            if (!Schema::hasColumn('contratos', 'resolucao')) {
                $table->string('resolucao')->nullable();
            }
            if (!Schema::hasColumn('contratos', 'latitude')) {
                $table->decimal('latitude', 10, 6)->nullable();
            }
            if (!Schema::hasColumn('contratos', 'longitude')) {
                $table->decimal('longitude', 10, 6)->nullable();
            }
            if (!Schema::hasColumn('contratos', 'ip')) {
                $table->string('ip', 45)->nullable();
            }
            if (!Schema::hasColumn('contratos', 'pdf_contrato')) {
                $table->string('pdf_contrato')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->dropColumn([
                'aceito_em',
                'navegador_info',
                'resolucao',
                'latitude',
                'longitude',
                'ip',
                'pdf_contrato'
            ]);
        });
    }
};
