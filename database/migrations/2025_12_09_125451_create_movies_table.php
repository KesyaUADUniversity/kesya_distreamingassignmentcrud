<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description');
            $table->decimal('rating', 3, 1)->nullable(); // tambahkan nullable
            $table->integer('release_year')->nullable();  // tambahkan nullable
            $table->unsignedBigInteger('category_id');
            $table->string('thumbnail')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('movie_categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('movies');
    }
}