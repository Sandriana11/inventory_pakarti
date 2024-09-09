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
        Schema::create('pengadaan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor'); 
            $table->string('supplier'); 
            $table->enum('tipe', ['beli', 'sewa']); 
            $table->string('nama'); 
            $table->unsignedBigInteger('kategori_id'); 
            $table->integer('qty')->default(0); 
            $table->float('field')->default(0); 
            $table->float('total')->default(0); 
            $table->unsignedBigInteger('lokasi_id'); 
            $table->date('tgl'); 
            $table->text('deskripsi')->nullable(); 
            $table->enum('status', ['draft', 'setuju', 'tolak']); 
            $table->timestamps();

            // foreign key constraints
            $table->foreign('kategori_id')->references('id')->on('kategoris')->onDelete('cascade');
            $table->foreign('lokasi_id')->references('id')->on('lokasis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengadaan');
    }
};
