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
			'vessel_service',
			function (Blueprint $table) {
            
                $table->char('service', 10);
                $table->integer('seq_no', 4);
                $table->char('disch_port', 5);
                $table->timestamp('update_time');

                $table->foreign('seq_no')->references('ves_seq')->on('vessel_seq')->onDelete('cascade');
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
