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
        Schema::create('consorcios', function (Blueprint $table) {
            $table->id();
            $table->string('plano');
            $table->integer('prazo'); // em meses
            $table->decimal('valor_total', 10, 2);
            $table->decimal('parcela_mensal', 10, 2);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consorcios');
    }
};
