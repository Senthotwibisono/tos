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
			'imocode',
			function (Blueprint $table) {
        $table->bigIncrements('imo_code');
        $table->char('kelas', 5);
        $table->string('klasifikasi_imo');
        $table->string('kategori');
        $table->string('nama_bahan');
        $table->string('sifat_sifat');
        $table->string('keterangan');
            
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
