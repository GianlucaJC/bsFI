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
        Schema::create('sotto_cat_doc_utili', function (Blueprint $table) {
            $table->id();
			$table->integer('id_categoria')->index();
			$table->integer('dele');
			$table->string('descrizione',200);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sotto_cat_doc_utilis');
    }
};
