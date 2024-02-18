<?php

use App\Domains\Enterprise\database\seeds\EnterpriseSeeder;
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
        $this->call(App\Domains\Admin\database\seeds\CreateAdminAccountSeeder::class);
         $this->call(App\Domains\Admin\database\seeds\SeedingPermissions::class);
//        $this->call(EnterpriseSeeder::class);
//        $this->call(CountriesTableSeeder::class);
    }
}
