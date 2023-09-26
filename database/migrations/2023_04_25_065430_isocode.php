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
			'isocode',
			function (Blueprint $table) {
            
                $table->bigIncrements('iso_code');
                $table->char('iso_size', 4);
                $table->char('iso_type', 3);
                $table->double('iso_weight', 9,3);
                $table->double('iso_height', 9,3);
                $table->char('iso_desc', 50);
                $table->timestamp('update_time');
                $table->unsignedBigInteger('user_id');

                $table->foreign('user_id')->references('id')->on('users');
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
