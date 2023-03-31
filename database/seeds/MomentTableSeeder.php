<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MomentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('moment')->delete();
        DB::table('moment')->insert(
            [
                'id' => 1,
                'name' => 'AFTER_REGISTERED',
                'status' => 'ACTIVE',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        );
        DB::table('moment')->insert(
            [
                'id' => 2,
                'name' => 'AFTER_DOWNLOAD_APP',
                'status' => 'ACTIVE',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        );
        DB::table('moment')->insert(
            [
                'id' => 3,
                'name' => 'BIRTHDAY',
                'status' => 'ACTIVE',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        );
    }
}
