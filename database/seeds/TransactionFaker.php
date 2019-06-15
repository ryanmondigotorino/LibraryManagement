<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class TransactionFaker extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
    	foreach (range(1,250) as $index) {
	        DB::table('borrows')->insert([
                'student_id' => rand(1,200),
	            'books' => "{\"".rand(1,7)."\":1}",
	            'return_in' => strtotime("+5 weekday",time()),
                'borrowed_status' => 'returned',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
	        ]);
	    }
    }
}