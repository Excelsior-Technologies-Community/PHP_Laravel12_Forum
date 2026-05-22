<?php
// database/seeders/CategorySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'General Discussion', 'slug' => 'general', 'description' => 'Talk about anything here'],
            ['name' => 'Laravel', 'slug' => 'laravel', 'description' => 'Laravel framework discussions'],
            ['name' => 'PHP', 'slug' => 'php', 'description' => 'PHP programming language'],
            ['name' => 'Frontend', 'slug' => 'frontend', 'description' => 'HTML, CSS, JavaScript'],
            ['name' => 'Database', 'slug' => 'database', 'description' => 'MySQL, PostgreSQL, etc.'],
            ['name' => 'Announcements', 'slug' => 'announcements', 'description' => 'Forum announcements'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}