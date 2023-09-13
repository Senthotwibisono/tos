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
        Schema::create(
			'vessel_seq',
			function (Blueprint $table) {
            
                $table->unsignedBigInteger('ves_code');
                $table->integer('ves_seq', 3);

                $table->foreign('ves_code')->references('ves_code')->on('vessel_master')->onDelete('cascade');
            }
            );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
