<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'name'      => 'Admin',
            'email'     => 'admin@admin.com',
            'password'  => bcrypt('password')
        ];

        User::insert($data);
    }
}
