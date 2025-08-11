<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;
use App\Models\Post;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy user đầu tiên
        $user = User::first();
        $post = Post::first();
        
        if ($user && $post) {
            Comment::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'content' => 'Bình luận mẫu để test chức năng admin',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            Comment::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'content' => 'Bình luận thứ hai để test',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Comments seeded successfully!');
        } else {
            $this->command->error('No users or posts found. Please seed users and posts first.');
        }
    }
}
