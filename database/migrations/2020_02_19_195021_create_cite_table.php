<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cite', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cite_id');
            $table->unsignedBigInteger('citeur_id');

            $table->foreign('cite_id')->references('id')->on('articles');
            $table->foreign('citeur_id')->references('id')->on('articles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cite');
    }
}
