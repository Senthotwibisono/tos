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
			'agent',
			function (Blueprint $table) {
                $table->bigIncrements('agent', 4)->unique();
				$table->char('shipper', 4)->nuulable();
				$table->char('oper_name', 10)->nuulable();
				$table->datetime('update_time');
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
