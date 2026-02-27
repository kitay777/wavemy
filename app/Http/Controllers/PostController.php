<?php

// app/Http/Controllers/PostController.php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Inertia\Inertia;

class PostController extends Controller
{
    public function dashboard()
    {
        $categories = Category::orderBy('sort_order')->get();

        $posts = Post::with(['user','category'])
            ->latest()
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'user' => [
                        'name' => $post->user->name,
                    ],
                    'category_id' => $post->category_id,
                    'content' => $post->content,
                    'goal' => $post->goal,
                    'echo_count' => $post->echoedUsers()->count(),
                    'is_echoed' => $post->echoedUsers()
                        ->where('user_id', auth()->id())
                        ->exists(),
                ];
            });

        return Inertia::render('Dashboard', [
            'categories' => $categories,
            'posts' => $posts
        ]);
    }

    public function echo(Post $post)
    {
        $user = auth()->user();

        if ($post->echoedUsers()->where('user_id', $user->id)->exists()) {
            $post->echoedUsers()->detach($user->id);
            $isEchoed = false;
        } else {
            $post->echoedUsers()->attach($user->id);
            $isEchoed = true;
        }

        return response()->json([
            'echo_count' => $post->echoedUsers()->count(),
            'is_echoed'  => $isEchoed
        ]);
    }
}