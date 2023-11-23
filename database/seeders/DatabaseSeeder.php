<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//         \App\Models\User::factory()->create([
//             'name' => 'Test User',
//             'email' => 'test@example.com',
//             'password' => bcrypt('password')
//         ]);
//
//        \App\Models\User::factory()->create([
//            'name' => 'Test User2',
//            'email' => 'test2@example.com',
//            'password' => bcrypt('password')
//        ]);

        Task::factory()->count(100)->create();

    }
}
