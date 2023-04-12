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
        Schema::create('aziende_custom', function (Blueprint $table) {
            $table->id();
			$table->integer('dele');
			$table->string('id_fiscale',20)->index()->nullable();
			$table->string('azienda',100)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aziende_customs');
    }
};
