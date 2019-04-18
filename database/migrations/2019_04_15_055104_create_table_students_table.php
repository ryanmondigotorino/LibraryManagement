<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('student_num');
            $table->integer('course_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->string('firstname',50);
            $table->string('middlename',50);
            $table->string('lastname',50);
            $table->string('image',100)->nullable();
            $table->text('address')->nullable();
            $table->bigInteger('contact_num')->nullable();
            $table->string('email',50);
            $table->string('username',50)->nullable();
            $table->string('password',100)->nullable();
            $table->integer('account_line');
            $table->integer('account_status');
            $table->integer('date_registered');
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('students');
    }
}
