<?php
namespace Laravelpkg\Laravelchk\database\seeds;
use Laravelpkg\Laravelchk\database\seeds\CredentialTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(CredentialTableSeeder::class);
    }
}
