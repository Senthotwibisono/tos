<div class="card">
 	<div class="card-header py-0">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item" role="presentation"><a class="nav-link active"
				id="cont-tab" data-bs-toggle="tab" href="#cont" role="tab"
				aria-controls="cont" aria-selected="true">Container</a></li>
			<li class="nav-item" role="presentation"><a class="nav-link"
				id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
				aria-controls="profile" aria-selected="false">Destination</a></li>
			<li class="nav-item" role="presentation"><a class="nav-link"
				id="contact-tab" data-bs-toggle="tab" href="#contact" role="tab"
				aria-controls="contact" aria-selected="false">Truck</a></li>
			<li class="nav-item" role="presentation"><a class="nav-link"
				id="contact1-tab" data-bs-toggle="tab" href="#contact1" role="tab"
				aria-controls="contact1" aria-selected="false">Customs</a></li>
		</ul>
	</div>
 	<div class="card-body py-0 px-0">
		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active" id="cont" role="tabpanel"
				aria-labelledby="cont-tab">
				<div class="row">
					<x-form-item id="container_no" label="Container no" md="md-4"
						value="{{ $item->container_no }}"></x-form-item>
					<x-form-item id="ves_id" label="Vessel ID" md="md-4"
						value="{{ $item->ves_id }}"></x-form-item>
					<x-form-item id="ves_code" label="Vessel Code" md="md-4"
						value="{{ $item->ves_code }}"></x-form-item>
					<x-form-item id="ves_name" label="Vessel Name" md="md-4"
						value="{{ $item->ves_name }}"></x-form-item>
					<x-form-item id="voy_no" label="voy_no" md="md-4"
						value="{{ $item->voy_no }}"></x-form-item>
					<x-form-item id="ctr_i_e_t" label="ctr_i_e_t" md="md-4"
						value="{{ $item->ctr_i_e_t }}"></x-form-item>
					<x-form-item id="ctps_yn" label="ctps_yn" md="md-4"
						value="{{ $item->ctps_yn }}"></x-form-item>
					<x-form-item id="ctr_active_yn" label="ctr_active_yn" md="md-4"
						value="{{ $item->ctr_active_yn }}"></x-form-item>
					<x-form-item id="ctr_size" label="ctr_size" md="md-4"
						value="{{ $item->ctr_size }}"></x-form-item>
					<x-form-item id="ctr_type" label="ctr_type" md="md-4"
						value="{{ $item->ctr_type }}"></x-form-item>
					<x-form-item id="ctr_status" label="ctr_status" md="md-4"
						value="{{ $item->ctr_status }}"></x-form-item>
					<x-form-item id="ctr_intern_status" label="ctr_intern_status"
						md="md-4" value="{{ $item->ctr_intern_status }}"></x-form-item>
					<x-form-item id="disc_load_trans_shift"
						label="disc_load_trans_shift" md="md-4"
						value="{{ $item->disc_load_trans_shift }}"></x-form-item>
					<x-form-item id="land_ship_crane" label="land_ship_crane" md="md-4"
						value="{{ $item->land_ship_crane }}"></x-form-item>
					<x-form-item id="shift_by" label="shift_by" md="md-4"
						value="{{ $item->shift_by }}"></x-form-item>
					<x-form-item id="gross" label="gross" md="md-4"
						value="{{ $item->gross }}"></x-form-item>
					<x-form-item id="gross_class" label="gross_class" md="md-4"
						value="{{ $item->gross_class }}"></x-form-item>
					<x-form-item id="over_height" label="over_height" md="md-4"
						value="{{ $item->over_height }}"></x-form-item>
					<x-form-item id="over_weight" label="over_weight" md="md-4"
						value="{{ $item->over_weight }}"></x-form-item>
					<x-form-item id="over_length" label="over_length" md="md-4"
						value="{{ $item->over_length }}"></x-form-item>
					<x-form-item id="commodity_code" label="commodity_code" md="md-4"
						value="{{ $item->commodity_code }}"></x-form-item>
				</div>
			</div>
			<div class="tab-pane fade" id="profile" role="tabpanel"
				aria-labelledby="profile-tab">
				<div class="row">
					<x-form-item id="commodity_name" label="commodity_name" md="md-4"
						value="{{ $item->commodity_name }}"></x-form-item>
					<x-form-item id="org_port" label="org_port" md="md-4"
						value="{{ $item->org_port }}"></x-form-item>
					<x-form-item id="load_port" label="load_port" md="md-4"
						value="{{ $item->load_port }}"></x-form-item>
					<x-form-item id="disch_port" label="disch_port" md="md-4"
						value="{{ $item->disch_port }}"></x-form-item>
					<x-form-item id="fdisch_port" label="fdisch_port" md="md-4"
						value="{{ $item->fdisch_port }}"></x-form-item>
					<x-form-item id="shipper" label="shipper" md="md-4"
						value="{{ $item->shipper }}"></x-form-item>
					<x-form-item id="agent" label="agent" md="md-4"
						value="{{ $item->agent }}"></x-form-item>
					<x-form-item id="consignee" label="consignee" md="md-4"
						value="{{ $item->consignee }}"></x-form-item>
					<x-form-item id="chilled_temp" label="chilled_temp" md="md-4"
						value="{{ $item->chilled_temp }}"></x-form-item>
					<x-form-item id="imo_code" label="imo_code" md="md-4"
						value="{{ $item->imo_code }}"></x-form-item>
					<x-form-item id="dangerous_yn" label="dangerous_yn" md="md-4"
						value="{{ $item->dangerous_yn }}"></x-form-item>
					<x-form-item id="dangerous_label_yn" label="dangerous_label_yn"
						md="md-4" value="{{ $item->dangerous_label_yn }}"></x-form-item>
					<x-form-item id="bl_no" label="bl_no" md="md-4"
						value="{{ $item->bl_no }}"></x-form-item>
					<x-form-item id="do_no" label="do_no" md="md-4"
						value="{{ $item->do_no }}"></x-form-item>
					<x-form-item id="seal_no" label="seal_no" md="md-4"
						value="{{ $item->seal_no }}"></x-form-item>
					<x-form-item id="peb_exp_no" label="peb_exp_no" md="md-4"
						value="{{ $item->peb_exp_no }}"></x-form-item>
					<x-form-item id="ctps_no" label="ctps_no" md="md-4"
						value="{{ $item->ctps_no }}"></x-form-item>
					<x-form-item id="pib_imp_no" label="pib_imp_no" md="md-4"
						value="{{ $item->pib_imp_no }}"></x-form-item>
					<x-form-item id="job_no" label="job_no" md="md-4"
						value="{{ $item->job_no }}"></x-form-item>
					<x-form-item id="invoice_no" label="invoice_no" md="md-4"
						value="{{ $item->invoice_no }}"></x-form-item>
					<x-form-item id="disc_load_seq" label="disc_load_seq" md="md-4"
						value="{{ $item->disc_load_seq }}"></x-form-item>
				</div>
			</div>
			<div class="tab-pane fade" id="contact" role="tabpanel"
				aria-labelledby="contact-tab">
				<div class="row">
					<x-form-item id="bay_slot" label="bay_slot" md="md-4"
						value="{{ $item->bay_slot }}"></x-form-item>
					<x-form-item id="bay_row" label="bay_row" md="md-4"
						value="{{ $item->bay_row }}"></x-form-item>
					<x-form-item id="bay_tier" label="bay_tier" md="md-4"
						value="{{ $item->bay_tier }}"></x-form-item>
					<x-form-item id="yard_block" label="yard_block" md="md-4"
						value="{{ $item->yard_block }}"></x-form-item>
					<x-form-item id="yard_slot" label="yard_slot" md="md-4"
						value="{{ $item->yard_slot }}"></x-form-item>
					<x-form-item id="yard_row" label="yard_row" md="md-4"
						value="{{ $item->yard_row }}"></x-form-item>
					<x-form-item id="yard_tier" label="yard_tier" md="md-4"
						value="{{ $item->yard_tier }}"></x-form-item>
					<x-form-item id="sp2_ke_date" label="sp2_ke_date" md="md-4"
						value="{{ $item->sp2_ke_date }}"></x-form-item>
					<x-form-item id="ctps_to_peb_date" label="ctps_to_peb_date"
						md="md-4" value="{{ $item->ctps_to_peb_date }}"></x-form-item>
					<x-form-item id="disc_date" label="disc_date" md="md-4"
						value="{{ $item->disc_date }}"></x-form-item>
					<x-form-item id="load_date" label="load_date" md="md-4"
						value="{{ $item->load_date }}"></x-form-item>
					<x-form-item id="stack_date" label="stack_date" md="md-4"
						value="{{ $item->stack_date }}"></x-form-item>
					<x-form-item id="truck_no" label="truck_no" md="md-4"
						value="{{ $item->truck_no }}"></x-form-item>
					<x-form-item id="truck_in_date" label="truck_in_date" md="md-4"
						value="{{ $item->truck_in_date }}"></x-form-item>
					<x-form-item id="truck_out_date" label="truck_out_date" md="md-4"
						value="{{ $item->truck_out_date }}"></x-form-item>
					<x-form-item id="arrival_carrier" label="arrival_carrier" md="md-4"
						value="{{ $item->arrival_carrier }}"></x-form-item>
					<x-form-item id="departure_carrier" label="departure_carrier"
						md="md-4" value="{{ $item->departure_carrier }}"></x-form-item>
					<x-form-item id="crane_no" label="crane_no" md="md-4"
						value="{{ $item->crane_no }}"></x-form-item>
					<x-form-item id="crane_oper" label="crane_oper" md="md-4"
						value="{{ $item->crane_oper }}"></x-form-item>
					<x-form-item id="ship_oa" label="ship_oa" md="md-4"
						value="{{ $item->ship_oa }}"></x-form-item>
					<x-form-item id="wharf_oa" label="wharf_oa" md="md-4"
						value="{{ $item->wharf_oa }}"></x-form-item>
				</div>
			</div>
			<div class="tab-pane fade" id="contact1" role="tabpanel"
				aria-labelledby="contact1-tab">
				<div class="row">
					<x-form-item id="ht_no" label="ht_no" md="md-4"
						value="{{ $item->ht_no }}"></x-form-item>
					<x-form-item id="ht_driver" label="ht_driver" md="md-4"
						value="{{ $item->ht_driver }}"></x-form-item>
					<x-form-item id="cc_tt_no" label="cc_tt_no" md="md-4"
						value="{{ $item->cc_tt_no }}"></x-form-item>
					<x-form-item id="cc_tt_oper" label="cc_tt_oper" md="md-4"
						value="{{ $item->cc_tt_oper }}"></x-form-item>
					<x-form-item id="wharf_yard_oa" label="wharf_yard_oa" md="md-4"
						value="{{ $item->wharf_yard_oa }}"></x-form-item>
					<x-form-item id="depot_warehouse_code" label="depot_warehouse_code" md="md-4"
						value="{{ $item->depot_warehouse_code }}"></x-form-item>
					<x-form-item id="container_dest" label="container_dest" md="md-4"
						value="{{ $item->container_dest }}"></x-form-item>
					<x-form-item id="remarks" label="remarks" md="md-4"
						value="{{ $item->remarks }}"></x-form-item>
					<x-form-item id="oper_name" label="oper_name" md="md-4"
						value="{{ $item->oper_name }}"></x-form-item>
					<x-form-item id="update_time" label="update_time" md="md-4"
						value="{{ $item->update_time }}"></x-form-item>
					<x-form-item id="iso_code" label="iso_code" md="md-4"
						value="{{ $item->iso_code }}"></x-form-item>
					<x-form-item id="no_permohonan_ob" label="no_permohonan_ob" md="md-4"
						value="{{ $item->no_permohonan_ob }}"></x-form-item>
					<x-form-item id="bhd_date" label="bhd_date" md="md-4"
						value="{{ $item->bhd_date }}"></x-form-item>
					<x-form-item id="stripping_date" label="stripping_date" md="md-4"
						value="{{ $item->stripping_date }}"></x-form-item>
					<x-form-item id="stuffing_date" label="stuffing_date" md="md-4"
						value="{{ $item->stuffing_date }}"></x-form-item>
					<x-form-item id="ctr_opr" label="ctr_opr" md="md-4"
						value="{{ $item->ctr_opr }}"></x-form-item>
					<x-form-item id="no_pos_bl" label="no_pos_bl" md="md-4"
						value="{{ $item->no_pos_bl }}"></x-form-item>
					<x-form-item id="ctr_vip_yn" label="ctr_vip_yn" md="md-4"
						value="{{ $item->ctr_vip_yn }}"></x-form-item>
					<x-form-item id="dg_uncode" label="dg_uncode" md="md-4"
						value="{{ $item->dg_uncode }}"></x-form-item>
					<x-form-item id="kpbc" label="kpbc" md="md-4"
						value="{{ $item->kpbc }}"></x-form-item>
					<x-form-item id="consl_code" label="consl_code" md="md-4"
						value="{{ $item->consl_code }}"></x-form-item>
					<x-form-item id="f_d_key" label="f_d_key" md="md-4"
						value="{{ $item->f_d_key }}"></x-form-item>
					<x-form-item id="consl_f_d_key" label="consl_f_d_key" md="md-4"
						value="{{ $item->consl_f_d_key }}"></x-form-item>
					<x-form-item id="is_damage" label="is_damage" md="md-4"
						value="{{ $item->is_damage }}"></x-form-item>
					<x-form-item id="user_id" label="user_id" md="md-4"
						value="{{ $item->user_id }}"></x-form-item>
				</div>
			</div>
		</div>

	</div>
</div>
