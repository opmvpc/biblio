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
            $table->string('auteurs', 2005);
            $table->string('titre', 505)->unique();
            $table->string('reference');
            $table->date('date');
            $table->text('resume')->nullable();
            $table->text('bibtex')->nullable();
            $table->string('url')->nullable();
            $table->text('abstract')->nullable();
            $table->string('doi')->nullable();
            $table->string('keywords')->nullable();
            $table->string('path_fiche_lecture')->nullable();
            $table->string('path_article')->nullable();
            $table->timestamps();

            $table->index('titre');
            $table->index('reference');
            $table->index('doi');
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
