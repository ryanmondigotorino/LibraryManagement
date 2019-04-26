<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableBooksChangeDescriptionType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE books MODIFY COLUMN description TEXT');
        DB::statement('ALTER TABLE authors MODIFY COLUMN favorite_quote TEXT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE books MODIFY COLUMN description VARCHAR(50)');
        DB::statement('ALTER TABLE authors MODIFY COLUMN favorite_quote VARCHAR(200)');
    }
}
