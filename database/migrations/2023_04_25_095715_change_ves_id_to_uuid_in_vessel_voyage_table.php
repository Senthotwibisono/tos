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
        Schema::table('vessel_voyage', function (Blueprint $table) {
            $table->uuid('ves_id') ->default(DB::raw('uuid_generate_v4()'))->primary();
            $table->uuid('ves_id')->change();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
        // Ubah kembali tipe data kolom 'ves_id' menjadi 'bigIncrements'
        Schema::table('vessel_voyage', function (Blueprint $table) {
            $table->bigIncrements('ves_id')->change();
        });
    }
};
