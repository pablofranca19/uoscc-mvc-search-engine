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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('osc_id')->constrained()->cascadeOnDelete();
            $table->string('tipo');
            $table->string('titulo');
            $table->date('data');
            $table->string('local');
            $table->string('regiao')->nullable();
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
        Schema::dropIfExists('activities');
    }
};
