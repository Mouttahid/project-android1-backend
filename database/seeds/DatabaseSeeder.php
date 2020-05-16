<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(RolesSeeder::class);
        // $this->call(UsersTableSeeder::class);
        $user = User::create([
            'name' => "admin admin",
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
        ]);
        $user->assignRole('Admin');
    }
}
