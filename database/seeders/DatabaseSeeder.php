<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Todo;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()
            ->has(Category::factory(rand(2,4))
                ->has(Todo::factory(rand(5, 21))))
            ->create([
            'name' => 'Brighton',
            'email' => 'brighton@vanrouendal.nl',
            'password' => Hash::make('password'),
        ]);
        User::factory(10)
            ->has(Category::factory(rand(2,4))
                ->has(Todo::factory(rand(5, 21))))
            ->create();
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
