<?php
namespace Laravelpkg\Laravelchk\database\seeds;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CredentialTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('soft_credentials')->insert([
            'key' => 'purchase_key',
            'value' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('soft_credentials')->insert([
            'key' => 'username',
            'value' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('soft_credentials')->insert([
            'key' => 'license_type',
            'value' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
