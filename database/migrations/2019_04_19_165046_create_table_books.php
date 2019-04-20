<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('added_by');
            $table->string('front_image',100)->nullable();
            $table->string('back_image',100)->nullable();
            $table->string('author',50)->nullable();
            $table->string('genre',50)->nullable();
            $table->string('title',50)->nullable();
            $table->string('description',50)->nullable();
            $table->integer('date_published')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('books');
    }
}
