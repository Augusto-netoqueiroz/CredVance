<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoletoLogsTable extends Migration
{
    public function up()
    {
        Schema::create('boleto_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pagamento_id')->unique(); // 1 log por pagamento
            $table->unsignedBigInteger('contrato_id');
            $table->unsignedBigInteger('cliente_id');

            $table->boolean('enviado')->default(false);
            $table->timestamp('enviado_em')->nullable();

            $table->boolean('aberto')->default(false);
            $table->timestamp('aberto_em')->nullable();

            $table->timestamps();

            $table->foreign('pagamento_id')->references('id')->on('pagamentos')->onDelete('cascade');
            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->foreign('cliente_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('boleto_logs');
    }
}
