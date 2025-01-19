<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userLogin = [[
            'name' => 'dapa',
            'email' => 'dapa@gmail.com',
            'password' => bcrypt('password'),
        ]
        ];

        foreach ($userLogin as $key => $val) {
            User::create($val);
        }
    }
}
