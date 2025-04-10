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
    Schema::table('users', function (Blueprint $table) {
        // Coluna para armazenar o código OTP (pode ser string, se quiser 6 dígitos por exemplo)
        $table->string('otp_code', 6)->nullable()->after('telefone');

        // Coluna para armazenar data/hora de expiração do código
        $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        // Remover as colunas se fizer rollback
        $table->dropColumn(['otp_code', 'otp_expires_at']);
    });
}

};
