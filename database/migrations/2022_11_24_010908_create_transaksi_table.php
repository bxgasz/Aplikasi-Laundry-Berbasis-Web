<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->increments('id_transaksi');
            $table->integer('id_outlet')->nullable();
            $table->string('kd_invoice');
            $table->integer('id_member')->nullable();
            $table->date('batas_waktu')->nullable();
            $table->date('tanggal_bayar')->nullable();
            $table->integer('biaya_tambahan');
            $table->integer('diskon')->default(0);
            $table->integer('pajak')->default(0);
            $table->integer('total_bayar')->default(0);
            $table->enum('status', ['baru','proses','selesai','diambil']);
            $table->enum('status_bayar', ['dibayar','belum_dibayar']);
            $table->integer('id_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
};
