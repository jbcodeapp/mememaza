<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       

        \App\Models\Admin\Admin::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => '$2y$10$ZCQbWerq142X0jF8JfBouOiw072afRDB8OXIKdSPwZRXgFdjhjOqu',
                // 12345678
                'status' => 1,
        ]);

        \App\Models\User::factory(20)->create();
        // \App\Models\Category::factory(10)->create();
        // \App\Models\Post::factory(100)->create();
    }
}
