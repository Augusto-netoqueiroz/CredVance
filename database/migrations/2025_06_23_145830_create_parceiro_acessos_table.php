<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parceiro_acessos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('parceiro_id')->constrained('parceiros');
    $table->ipAddress('ip')->nullable();
    $table->text('user_agent')->nullable();
    $table->enum('evento', ['acesso', 'cadastro', 'compra_cota', 'inadimplencia']);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parceiro_acessos');
    }
};
