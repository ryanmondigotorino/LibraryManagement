<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class BooksFaker extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $category = config('books.book_category');
    	foreach (range(1,200) as $index) {
	        DB::table('books')->insert([
                'added_by' => rand(1,9),
                'quantity' => rand(30,100),
                'disperse' => 0,
                'author_id' => rand(1,7),
                'genre' => $category[rand(0,9)],
                'title' => $faker->name,
                'description' => 'This is a test description',
                'date_published' => strtotime("-5 weekday",time()),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
	        ]);
	    }
    }
}