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
        Schema::create('rekening_admins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_bank');
            $table->foreign('id_bank')->references('id')->on('banks')->onUpdate('cascade')->onDelete('cascade');
            $table->string('nama_admin');
            $table->string('nomor_rekening');
            $table->string('nama_bank');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekening_admins');
    }
};
