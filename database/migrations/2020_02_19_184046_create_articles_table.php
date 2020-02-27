<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('titre', 305)->unique();
            $table->string('slug', 305)->unique();
            $table->string('reference')->nullable();
            $table->date('date');
            $table->text('resume')->nullable();
            $table->text('bibtex')->nullable();
            $table->string('slug', 305)->nullable();
            $table->text('abstract')->nullable();
            $table->string('doi')->nullable();
            $table->string('path_fiche_lecture')->nullable();
            $table->string('path_article')->nullable();
            $table->enum('pertinence', ['1', '2', '3'])->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('type_reference_id')->nullable();

            $table->index('titre');
            $table->index('reference');
            $table->index('doi');

            $table->foreign('type_reference_id')->references('id')->on('type_references');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
