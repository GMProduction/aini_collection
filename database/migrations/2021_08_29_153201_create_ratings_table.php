<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_produk')->unsigned()->nullable(true);
            $table->foreign('id_produk')->references('id')->on('produks');
            $table->bigInteger('id_user')->unsigned()->nullable(true);
            $table->foreign('id_user')->references('id')->on('users');
            $table->text('ulasan');
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
        Schema::dropIfExists('ratings');
    }
}
