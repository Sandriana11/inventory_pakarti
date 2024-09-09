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
        Schema::create('mainteners', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('tipe',['internal','external']);
            $table->string('hp');
            $table->text('alamat');
            $table->char('nip',15);
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
        Schema::dropIfExists('maintener');
    }
};
