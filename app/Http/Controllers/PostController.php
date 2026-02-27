<?php

// app/Http/Controllers/PostController.php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function dashboard()
    {
        $categories = Category::orderBy('sort_order')->get();

        $posts = Post::with(['user', 'category'])
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
                    'image' => $post->image ? asset('storage/' . $post->image) : null,
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
    public function create()
    {
        return Inertia::render('Posts/Create', [
            'categories' => Category::orderBy('sort_order')->get()
        ]);
    }

    // 投稿保存
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'goal' => 'required|integer|min:10',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('posts', 'public');
        }

        Post::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'category_id' => $request->category_id,
            'goal' => $request->goal,
            'status' => 'gathering',
            'image' => $imagePath
        ]);

        return redirect()->route('dashboard');
    }
}
