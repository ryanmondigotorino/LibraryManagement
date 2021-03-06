<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAdminAudits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_audits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('admin_id');
            $table->string('action',50);
            $table->string('ip_address',50)->nullable();
            $table->string('device',50)->nullable();
            $table->string('browser',50)->nullable();
            $table->string('operating_system',50)->nullable();
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
        Schema::dropIfExists('admin_audits');
    }
}
