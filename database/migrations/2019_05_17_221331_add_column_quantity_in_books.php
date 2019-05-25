<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnQuantityInBooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->bigInteger('disperse')->nullable()->after('back_image');
            $table->bigInteger('quantity')->nullable()->after('back_image');
            $table->bigInteger('status')->nullable()->after('date_published');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('disperse');
            $table->dropColumn('quantity');
            $table->dropColumn('status');
        });
    }
}
