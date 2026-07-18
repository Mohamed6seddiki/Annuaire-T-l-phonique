<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('standards', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->string('nom');
            $table->foreignId('id_direction')
                  ->constrained('directions')
                  ->onDelete('restrict');
            $table->foreignId('id_sdirection')
                  ->constrained('sdirections')
                  ->onDelete('restrict');
            $table->foreignId('id_departement')
                  ->constrained('departements')
                  ->onDelete('restrict');
            $table->string('service')->nullable();
            $table->foreignId('id_site')
                  ->constrained('sites')
                  ->onDelete('restrict');
            $table->string('niveau')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standards');
    }
};
