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
			'item',
			function (Blueprint $table) {
        $table->bigIncrements('container_key');
        $table->char('container_no',13)->nullable();
        $table->unsignedBigInteger('ves_id');
        $table->char('ves_code',7)->nullable();
        $table->char('voy_no',7)->nullable();
        $table->char('ctr_i_e_t',1)->nullable();
        $table->char('ctps_yn',1)->nullable();
        $table->char('ctr_active_yn',1)->nullable();
        $table->char('ctr_size',10)->nullable();
        $table->char('ctr_type',3)->nullable();
        $table->char('ctr_status',3)->nullable();
        $table->char('ctr_intern_status',2)->nullable();
        $table->char('disc_load_trans_shift',4)->nullable();
        $table->char('land_ship_crane',1)->nullable();
        $table->char('shift_by',3)->nullable();
        $table->double('gross',9,3)->nullable();
        $table->char('gross_class',1)->nullable();
        $table->char('over_height',5)->nullable();
        $table->char('over_weight',5)->nullable();
        $table->char('over_length',5)->nullable();
        $table->char('commodity_code',6)->nullable();
        $table->char('commodity_name',25)->nullable();
        $table->char('org_port',5)->nullable();
        $table->char('load_port',5)->nullable();
        $table->char('disch_port',5)->nullable();
        $table->char('fdisch_port',5)->nullable();
        $table->char('shipper',4)->nullable();
        $table->char('agent',4)->nullable();
        $table->char('consignee',50)->nullable();
        $table->char('chilled_temp',5,1)->nullable();
        $table->char('imo_code',6)->nullable();
        $table->char('dangerous_yn',1)->nullable();
        $table->char('dangerous_label_yn',1)->nullable();
        $table->char('bl_no',50)->nullable();
        $table->char('do_no',50)->nullable();
        $table->char('seal_no',20)->nullable();
        $table->char('peb_exp_no',20)->nullable();
        $table->char('ctps_no',20)->nullable();
        $table->char('pib_imp_no',20)->nullable();
        $table->char('job_no',7)->nullable();
        $table->char('invoice_no',7)->nullable();
        $table->char('disc_load_seq',4)->nullable();
        $table->char('bay_slot',2)->nullable();
        $table->char('bay_row',2)->nullable();
        $table->char('bay_tier',2)->nullable();
        $table->char('yard_block',5)->nullable();
        $table->char('yard_slot',2)->nullable();
        $table->char('yard_row',2)->nullable();
        $table->char('yard_tier',2)->nullable();
        $table->datetime('sp2_ke_date')->nullable();
        $table->datetime('ctps_to_peb_date')->nullable();
        $table->datetime('disc_date')->nullable();
        $table->datetime('load_date')->nullable();
        $table->datetime('stack_date')->nullable();
        $table->char('truck_no',12)->nullable();
        $table->datetime('truck_in_date');
        $table->datetime('truck_out_date');
        $table->char('arrival_carrier',20)->nullable();
        $table->char('departure_carrier',12)->nullable();
        $table->char('crane_no',6)->nullable();
        $table->char('crane_oper',6)->nullable();
        $table->char('ship_oa',6)->nullable();
        $table->char('wharf_oa',6)->nullable();
        $table->char('ht_no',6)->nullable();
        $table->char('ht_driver',6)->nullable();
        $table->char('cc_tt_no',6)->nullable();
        $table->char('cc_tt_oper',6)->nullable();
        $table->char('wharf_yard_oa',6)->nullable();
        $table->char('depot_warehouse_code',6)->nullable();
        $table->char('container_dest',30)->nullable();
        $table->char('remarks',20)->nullable();
        $table->char('oper_name',10)->nullable();
        $table->timestamp('update_time',);
        $table->unsignedBigInteger('iso_code',);
        $table->char('no_permohonan_ob',30)->nullable();
        $table->datetime('bhd_date')->nullable();
        $table->datetime('stripping_date')->nullable();
        $table->datetime('stuffing_date')->nullable();
        $table->char('ctr_opr',6)->nullable();
        $table->char('no_pos_bl',10)->nullable();
        $table->char('ctr_vip_yn',1)->nullable();
        $table->char('dg_uncode',6)->nullable();
        $table->char('kpbc',10)->nullable();
        $table->char('consl_code',4)->nullable();
        $table->char('f_d_key',10)->nullable();
        $table->char('consl_f_d_key',10)->nullable();
        $table->char('is_damage',1)->nullable();
        $table->unsignedBigInteger('user_id');
        
        $table->foreign('ves_id')->references('ves_id')->on('vessel_voyage');
        $table->foreign('iso_code')->references('iso_code')->on('isocode');
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
