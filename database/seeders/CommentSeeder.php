<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;
use App\Models\Product;
use App\Models\Post;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy một số user, product và post để tạo comments
        $users = User::take(5)->get();
        $products = Product::take(3)->get();
        $posts = Post::take(2)->get();

        if ($users->isEmpty() || $products->isEmpty() || $posts->isEmpty()) {
            return;
        }

        // Tạo comments cho products
        foreach ($products as $product) {
            foreach ($users->take(3) as $user) {
                Comment::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'content' => 'Sản phẩm rất tốt, chất lượng cao!',
                    'status' => 'approved',
                    'rating' => rand(4, 5),
                ]);

                Comment::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'content' => 'Giao hàng nhanh, đóng gói cẩn thận.',
                    'status' => 'pending',
                    'rating' => rand(3, 5),
                ]);
            }
        }

        // Tạo comments cho posts
        foreach ($posts as $post) {
            foreach ($users->take(2) as $user) {
                Comment::create([
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                    'content' => 'Bài viết rất hay và hữu ích!',
                    'status' => 'approved',
                ]);

                Comment::create([
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                    'content' => 'Cảm ơn tác giả đã chia sẻ thông tin này.',
                    'status' => 'rejected',
                ]);
            }
        }

        // Tạo một số comments không có user (khách)
        foreach ($products->take(2) as $product) {
            Comment::create([
                'product_id' => $product->id,
                'content' => 'Sản phẩm đẹp, giá hợp lý.',
                'status' => 'approved',
                'rating' => 4,
            ]);
        }
    }
}
