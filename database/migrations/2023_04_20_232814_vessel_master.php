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
			'vessel_master',
			function (Blueprint $table) {

                $table->bigIncrements('ves_code', 4);
                $table->char('ves_name', 25)->nullable();
                $table->char('agent', 4)->nullable();
                $table->char('liner', 4)->nullable();
                $table->char('liner_name', 50)->nullable();
                $table->char('reg_flag', 25)->nullable();
                $table->char('ocean_interisland', 1)->nullable();
                $table->double('g_r_t', 9, 3)->nullable();
                $table->double('b_r_t', 9,3)->nullable();
                $table->double('l_o_a', 9,3)->nullable();
                $table->double('d_w_t', 9,3)->nullable();
                $table->double('n_r_t', 9,3)->nullable();
                $table->double('draft', 9,3)->nullable();
                $table->double('ves_length', 9,3)->nullable();
                $table->date('year_made')->nullable();
                $table->char('country_made', 25)->nullable();
                $table->char('call_sign', 20)->nullable();
                $table->char('lloyds_no', 20)->nullable();
                $table->char('isps_code', 20)->nullable();
                $table->date('isps_date')->nullable();
                $table->char('remark', 20)->nullable();
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
