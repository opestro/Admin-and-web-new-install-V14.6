<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SellerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sellers')->insert([
            'f_name' => 'al imrun',
            'l_name' => 'khandakar',
            'phone' => '01759412381',
            'email' => 'seller@seller.com',
            'image' => 'def.png',
            'password' => bcrypt(12345678),
            'status'=>'pending',
            'remember_token' =>Str::random(10),
            'created_at'=>now(),
            'updated_at'=>now()
        ]);
    }
}
