<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;   // ← ここ！

class ExploreController extends Controller
{
public function index()
{
    $categories = Category::orderBy('sort_order')->get();

    $trending = Post::withCount('echoedUsers')
        ->get()
        ->map(function ($post) {

            $recentEcho = $post->echoedUsers()
                ->where('echoes.created_at', '>=', now()->subHours(24))
                ->count();

            $score =
                ($post->echoed_users_count * 2) +
                ($recentEcho * 3);

            return [
                'id' => $post->id,
                'content' => $post->content,
                'echo_count' => $post->echoed_users_count,
                'score' => $score,
                'image' => $post->image ? asset('storage/' . $post->image) : null,
            ];
        })
        ->sortByDesc('score')
        ->take(10)
        ->values();

    $creators = User::withCount('posts')
        ->orderByDesc('posts_count')
        ->take(5)
        ->get();

    return Inertia::render('Explore', [
        'categories' => $categories,
        'trending'   => $trending,
        'creators'   => $creators
    ]);
}
}