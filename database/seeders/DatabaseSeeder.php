<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Contest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Тестовые пользователи
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Jury User',
            'email' => 'jury@example.com',
            'password' => Hash::make('jury123123123'),
            'role' => 'jury',
        ]);

        User::create([
            'name' => 'Participant User',
            'email' => 'participant@example.com',
            'password' => Hash::make('participant123123'),
            'role' => 'participant',
        ]);

        // Тестовый конкурс
        Contest::create([
            'title' => 'Конкурс проектов 2028',
            'description' => 'Описание конкурса проектов',
            'deadline_at' => now()->addDays(30),
            'is_active' => true,
        ]);
    }
}