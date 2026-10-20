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
        Schema::create('eventos', function (Blueprint $table): void {
            $table->id();
            $table->string('titulo');
            $table->string('organizacao');
            $table->date('data');
            $table->string('hora');
            $table->string('local');
            $table->string('regiao');
            $table->string('tipo');
            $table->unsignedInteger('vagas')->default(0);
            $table->text('descricao');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
