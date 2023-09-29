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
			'berth',
			function (Blueprint $table) {
                $table->bigIncrements('berth_no', 4);
                $table->char('code', 1);
				$table->char('consturct_type', 1)->nuulable();
                $table->double('from_length', 9,3);
                $table->double('to_length', 9,3);
                $table->char('ocean_interisland', 1);
                $table->char('oper_name', 1);
				$table->datetime('update_time');
				$table->char('color', 11)->nuulable();
                $table->char('x', 11)->nuulable();
                $table->char('Y', 11)->nuulable();
                $table->char('horz_vert', 1)->nuulable();
                $table->char('height', 11)->nuulable();
                $table->char('sea_side', 1)->nuulable();
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
