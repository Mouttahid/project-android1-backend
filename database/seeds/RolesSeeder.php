<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Role::create(['guard_name' => 'api', 'name' => 'Admin']);
        Role::create(['guard_name' => 'api', 'name' => "Team Chief"]);
        Role::create(['guard_name' => 'api', 'name' => 'Worker']);
    }
}
