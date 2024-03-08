<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(
			'vessel_voyage',
			function (Blueprint $table) {

                $table->uuid('ves_id')->primary();
                $table->unsignedBigInteger('ves_code');
                $table->char('voy_in', 7)->nullable();
                $table->char('voy_out', 7)->nullable();
                $table->char('ves_name', 25)->nullable();
                $table->char('agent', 4)->nullable();
                $table->char('liner', 4)->nullable();
                $table->char('voyage_owner', 4)->nullable();
                $table->char('import_yn', 1)->nullable();
                $table->char('export_yn', 1)->nullable();
                $table->double('ves_length', 9,3)->nullable();
                $table->char('reg_flag', 25)->nullable();
                $table->char('ocean_interisland', 1)->nullable();
                $table->char('berth_no', 5)->nullable();
                $table->char('berth_fr_metre', 4)->nullable();
                $table->char('berth_to_metre', 4)->nullable();
                $table->char('btoa_side', 1)->nullable();
                $table->char('berth_grid', 5)->nullable();
                $table->datetime('est_anchorage_date');
                $table->datetime('act_anchorage_date');
                $table->datetime('est_end_work_date');
                $table->datetime('act_end_work_date');
                $table->datetime('eta_date');
                $table->datetime('arrival_date');
                $table->datetime('etd_date');
                $table->datetime('deparature_date');
                $table->datetime('est_berthing_date');
                $table->datetime('berthing_date');
                $table->datetime('discharge_date');
                $table->datetime('loading_date');
                $table->datetime('exit_date');
                $table->datetime('clossing_date');
                $table->datetime('doc_clossing_date');
                $table->datetime('recv_cargo_cutoff_date');
                $table->double('non_oper_time', 9, 3)->nullable();
                $table->double('idle_time', 9, 3)->nullable();
                $table->double('effective_time', 9, 3)->nullable();
                $table->date('year_made')->nullable();
                $table->char('country_made', 25)->nullable();
                $table->char('last_port', 5)->nullable();
                $table->char('next_port', 5)->nullable();
                $table->char('origin_port', 5)->nullable();
                $table->char('dest_port', 5)->nullable();
                $table->char('liner_tramp', 1)->nullable();
                $table->char('feeder_direct', 1)->nullable();
                $table->char('export_booking', 4)->nullable();
                $table->char('export_counter', 4)->nullable();
                $table->char('import_booking', 4)->nullable();
                $table->char('import_counter', 4)->nullable();
                $table->char('vessel_service', 10)->nullable();
              
                $table->char('billing_complate', 1)->nullable();
                $table->char('remarks', 50)->nullable();
                $table->timestamp('update_time');
                $table->unsignedBigInteger('user_id');
                $table->char('vessel_history', 1)->nullable();
                $table->char('berth_code', 1)->nullable();
                $table->char('cy_code', 1)->nullable();
                $table->char('no_bc11', 15)->nullable();


                
                $table->foreign('ves_code')->references('ves_code')->on('vessel_master')->onDelete('cascade');
                $table->foreign('ocean_interisland')->references('ves_code')->on('vessel_master')->onDelete('cascade');
                $table->foreign('liner')->references('ves_code')->on('vessel_master')->onDelete('cascade');
                $table->foreign('reg_flag')->references('ves_code')->on('vessel_master')->onDelete('cascade');
                $table->foreign('berth_code')->references('berth_no')->on('berth')->onDelete('cascade');
                $table->foreign('berth_no')->references('berth_no')->on('berth')->onDelete('cascade');
                $table->foreign('berth_fr_metre')->references('berth_no')->on('berth')->onDelete('cascade');
                $table->foreign('berth_to_metre')->references('berth_no')->on('berth')->onDelete('cascade');
                $table->foreign('vessel_service')->references('service')->on('ves_service')->onDelete('cascade');
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
