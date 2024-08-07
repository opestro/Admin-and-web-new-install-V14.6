<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminRoleTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_roles')->insert([
            'id' => 1,
            'name' => 'Master Admin',
        ]);

        DB::table('admin_roles')->insert([
            'id' => 2,
            'name' => 'Employee',
        ]);
    }
}
