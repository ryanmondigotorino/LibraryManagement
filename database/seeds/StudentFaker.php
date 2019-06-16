<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use ClassFactory as CF;
class StudentFaker extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
    	foreach (range(1,5000) as $index) {
            $studentnum = CF::model('Student')->select('id')->withTrashed()->orderBy('id','desc')->limit(1);
            $getStudentNumber = $studentnum->count() > 0 ? $studentnum->get()[0]->id + 1 : 1;
	        DB::table('students')->insert([
                'student_num' => date('Y').str_pad($getStudentNumber, 5, '0', STR_PAD_LEFT),
                'course_id' => 1,
                'department_id' => 1,
	            'firstname' => $faker->firstName,
	            'lastname' => $faker->lastName,
                'email' => $faker->email,
                'username' => $faker->username,
                'password' => bcrypt("123456789"),
                'account_line' => 0,
                'account_status' => 1,
                'date_registered' => time(),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
	        ]);
	    }
    }
}