<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin_user',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $author = User::create([
            'name' => 'Author User',
            'username' => 'author_user',
            'email' => 'author@example.com',
            'password' => Hash::make('password'),
            'role' => 'author',
        ]);

        $reader = User::create([
            'name' => 'Reader User',
            'username' => 'reader_user',
            'email' => 'reader@example.com',
            'password' => Hash::make('password'),
            'role' => 'reader',
        ]);

        for ($i = 1; $i <= 5; $i++) {
            Book::create([
                'title' => 'Sample Book ' . $i,
                'summary' => 'This is a sample summary for book ' . $i . '. It contains interesting stories and imaginative worlds.',
                'author_id' => $author->id,
                'genre' => ['Fantasy', 'Sci-Fi', 'Mystery', 'Romance', 'Thriller'][rand(0, 4)],
                'pdf_path' => 'sample.pdf',
                'status' => 'published',
                'admin_status' => 'approved',
                'is_featured' => rand(0, 1) === 1,
            ]);
        }
    }
}
