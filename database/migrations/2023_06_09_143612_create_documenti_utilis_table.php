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
        Schema::create('documenti_utili', function (Blueprint $table) {
            $table->id();
			$table->integer('dele');
			$table->integer('id_funzionario')->index();
			$table->string('filename',50);
			$table->string('file_user',100);
			$table->string('url_completo',100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documenti_utilis');
    }
};
