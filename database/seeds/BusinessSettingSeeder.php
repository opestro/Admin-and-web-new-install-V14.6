<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('business_settings')->insert([
            'id' => 1,
            'type' => 'system_default_currency',
            'value' => 1,
        ]);
    }
}
