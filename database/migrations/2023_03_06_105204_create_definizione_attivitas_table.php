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
        Schema::create('definizione_attivita', function (Blueprint $table) {
            $table->id();
			$table->integer('dele');
			$table->integer('ref_categoria');
			$table->string('descrizione',150);				
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('definizione_attivita');
    }
};
