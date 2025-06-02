<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'username' => 'admin01',
                'fullname' => 'Nguyễn Văn Admin',
                'phone' => '0912345678',
                'email' => 'admin@duantn.com',
                'password' => Hash::make('123123123'),
                'status' => 'active',
                'gender' => 'male',
                'role' => 'admin',
                'avatar' => null,
                'is_verified' => 1,
                'birthday' => '1980-01-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'username' => 'seller01',
                'fullname' => 'Trần Thị Bán Hàng',
                'phone' => '0923456789',
                'email' => 'seller01@duantn.com',
                'password' => Hash::make('123123123'),
                'status' => 'active',
                'gender' => 'female',
                'role' => 'seller',
                'avatar' => 'avatars/seller01.jpg',
                'is_verified' => 1,
                'birthday' => '1990-05-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'username' => 'customer01',
                'fullname' => 'Lê Văn Khách',
                'phone' => '0934567890',
                'email' => 'customer01@duantn.com',
                'password' => Hash::make('123123123'),
                'status' => 'active',
                'gender' => 'male',
                'role' => 'customer',
                'avatar' => 'avatars/customer01.jpg',
                'is_verified' => 1,
                'birthday' => '1995-10-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'username' => 'employee01',
                'fullname' => 'Phạm Thị Nhân Viên',
                'phone' => '0945678901',
                'email' => 'employee01@duantn.com',
                'password' => Hash::make('123123123'),
                'status' => 'active',
                'gender' => 'female',
                'role' => 'employee',
                'avatar' => null,
                'is_verified' => 1,
                'birthday' => '1992-03-12',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}