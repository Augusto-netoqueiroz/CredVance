<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIntegraBoletoFieldsToPagamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Neste migration, adicionamos colunas para integração de boletos:
     * - codigo_solicitacao: identificador retornado pela API
     * - nosso_numero: número usado na API para identificar a cobrança
     * - status_solicitacao: status retornado pela API (pendente, emitido, liquidado etc.)
     * - data_emissao: datetime quando o boleto foi efetivamente emitido pelo Inter
     * - data_pagamento: datetime quando pago
     * - data_cancelamento: datetime se cancelado
     * - linha_digitavel: linha digitável retornada pela API
     * - url_boleto: URL de boleto, se API fornecer
     * - boleto_path: caminho local onde o PDF foi salvo
     * - json_resposta: json bruto da criação ou última consulta
     * - tentativas: contador de tentativas de chamada
     * - webhook_recebido: indica se já processou notificação (quando usar webhook; aqui default false)
     * - error_message: mensagem de erro em falhas permanentes
     */
    public function up()
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            // Identificador na API
            $table->string('codigo_solicitacao', 100)->nullable()->after('boleto');
            // Nosso número usado, caso queira armazenar explicitamente
            $table->string('nosso_numero', 50)->nullable()->after('codigo_solicitacao');
            // Status retornado pela API
            $table->string('status_solicitacao', 30)->nullable()->after('nosso_numero');
            // Datas de emissão, pagamento e cancelamento
            $table->timestamp('data_emissao')->nullable()->after('status_solicitacao');
            $table->timestamp('data_pagamento')->nullable()->after('data_emissao');
            $table->timestamp('data_cancelamento')->nullable()->after('data_pagamento');
            // Linha digitável
            $table->string('linha_digitavel', 100)->nullable()->after('data_cancelamento');
            // URL de boleto (se aplicável)
            $table->string('url_boleto', 255)->nullable()->after('linha_digitavel');
            // Caminho local do PDF gerado via API
            $table->string('boleto_path', 255)->nullable()->after('url_boleto');
            // JSON bruto para debug/auditoria
            $table->text('json_resposta')->nullable()->after('boleto_path');
            // Contador de tentativas
            $table->unsignedInteger('tentativas')->default(0)->after('json_resposta');
            // Indica se webhook já processou esta parcela
            $table->boolean('webhook_recebido')->default(false)->after('tentativas');
            // Mensagem de erro em falha permanente
            $table->text('error_message')->nullable()->after('webhook_recebido');
            
            // Índices para consultas frequentes
            $table->index('codigo_solicitacao');
            $table->index('status_solicitacao');
            $table->index('nosso_numero');
            // Talvez índice composto de status_solicitacao + vencimento em queries de polling
        });
    }

    /**
     * Reverse the migrations.
     *
     * Remove as colunas adicionadas.
     */
    public function down()
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropIndex(['codigo_solicitacao']);
            $table->dropIndex(['status_solicitacao']);
            $table->dropIndex(['nosso_numero']);

            $table->dropColumn([
                'codigo_solicitacao',
                'nosso_numero',
                'status_solicitacao',
                'data_emissao',
                'data_pagamento',
                'data_cancelamento',
                'linha_digitavel',
                'url_boleto',
                'boleto_path',
                'json_resposta',
                'tentativas',
                'webhook_recebido',
                'error_message',
            ]);
        });
    }
}