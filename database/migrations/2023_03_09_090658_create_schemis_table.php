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
        Schema::create('schemi', function (Blueprint $table) {
            $table->id();
			$table->integer('dele');
			$table->string('periodo',10);
			$table->date('periodo_data');
			$table->integer('id_funzionario')->index();
			$table->integer('id_categoria')->index();
			$table->integer('id_attivita')->index();
			$table->integer('id_settore')->index();
			$table->integer('valore')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schemis');
    }
};
