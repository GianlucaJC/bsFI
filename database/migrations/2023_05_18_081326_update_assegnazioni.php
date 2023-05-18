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
		Schema::table('assegnazioni', function ($table) {
			$table->string('id_fiscale',20)->index()->after('dele');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
