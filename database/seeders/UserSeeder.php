<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // HAPUS SEMUA USER DENGAN AMAN (FK-safe)
        User::query()->delete();

        $users = [
            [
                'name' => 'Syafriadi',
                'username' => 'syafriadi',
                'email' => 'syafriadi.za@gmail.com',
                'password' => Hash::make('Syafriadi123#'),
            ],
            [
                'name' => 'Ifnidawati',
                'username' => 'ifnidawati0305',
                'email' => 'ifnidawati0305@gmail.com',
                'password' => Hash::make('Ifnidawati123#'),
            ],
            [
                'name' => 'Desiyusmita',
                'username' => 'desiyusmita7',
                'email' => 'desiyusmita7@gmail.com',
                'password' => Hash::make('Desiyusmita123#'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
