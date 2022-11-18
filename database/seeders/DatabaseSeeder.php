<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Post;
use App\Models\User;
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

        for ($i = 1; $i <= 3; $i++) {
            $user = User::factory()->create();
            $posts = Post::factory()->count(3)->for($user)->create();
        }
    }
}
