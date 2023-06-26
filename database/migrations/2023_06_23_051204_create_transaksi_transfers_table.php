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
        Schema::create('transaksi_transfers', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('id_pengirim')->nullable();
             $table->unsignedBigInteger('id_penerima');
             $table->foreign('id_pengirim')->references('id')->on('users');
             $table->foreign('id_penerima')->references('id')->on('users');
            $table->string('id_transaksi')->unique();
            $table->decimal('nilai_transfer', 13, 2);
            $table->string('bank_tujuan');
            $table->string('rekening_tujuan');
            $table->string('atas_nama_tujuan');
            $table->string('bank_pengirim');
            $table->timestamps();

            $table->string('kode_unik')->nullable();
            $table->decimal('biaya_admin')->default(0);
            $table->decimal('total_transfer')->nullable();
            $table->string('bank_perantara')->nullable();
            $table->string('rekening_perantara')->nullable();
            $table->timestamp('berlaku_hingga')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_transfers');
    }
};
