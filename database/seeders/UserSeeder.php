<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'super@admin.com',
                'password' => bcrypt('password'),
            ],
            [
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
            ],
            [
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'test@user.com',
                'password' => bcrypt('password'),
            ],
        ];
        foreach($users as $user) {
            $exist = User::where('email',$user['email'])->first();
            if(!$exist) {
                User::create($user);
            }
        }
    }
}
