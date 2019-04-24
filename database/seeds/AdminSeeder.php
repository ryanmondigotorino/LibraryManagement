<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = array_map('str_getcsv', file(base_path('/database/seeds/csv/admin.csv')));

        if (is_array($csv) || is_object($csv)) {
            Schema::disableForeignKeyConstraints();
            DB::table('admins')->delete();
            $indx = 0;
            foreach ($csv as $key => $val) {
                $indx++;
                if ($indx == 1) continue;

                DB::table('admins')->insert([
                    'id' => $val[0],
                    'firstname' => $val[1],
                    'lastname' => $val[3],
                    'image' => $val[4],
                    'email' => $val[5],
                    'username' => $val[6],
                    'password' => bcrypt($val[7]),
                    'account_type' => $val[8],
                    'account_line' => $val[9],
                    'account_status' => $val[10],
                    'date_registered' => time(),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]);
            }
        }
    }
}
