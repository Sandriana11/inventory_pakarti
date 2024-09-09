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
        Schema::create('maintenance', function (Blueprint $table) {
            $table->id(); 
            $table->string('nomor'); 
            $table->unsignedBigInteger('crash_id'); 
            $table->unsignedBigInteger('eksekutor_id'); 
            $table->date('tgl');
            $table->date('target');
            $table->dateTime('tgl_selesai')->nullable(); 
            $table->unsignedBigInteger('id_penerima')->nullable(); 
            $table->tinyInteger('status')->default(0); 
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('penerima_id')->nullable(); 
            $table->timestamps(); 

            // foreign key constraints
            $table->foreign('crash_id')->references('id')->on('crashes')->onDelete('cascade');
            $table->foreign('eksekutor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('penerima_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maintenance');
    }
};
