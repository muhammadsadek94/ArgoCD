<?php

namespace App\Domains\Admin\database\seeds;

use App\Domains\Admin\Models\Admin;
use Illuminate\Database\Seeder;

class CreateAdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name'           => 'Admin',
            'email'          => 'admin@admin.com',
            'password'       => 123123,
            'is_super_admin' => 1,
            'activation'     => 1,
            'phone' => '201118240000'
        ]);
    }

}
