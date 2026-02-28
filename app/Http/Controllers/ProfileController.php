<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $user->load(['posts' => function ($query) {
            $query->latest();
        }]);

        return Inertia::render('Profile/Show2', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'posts' => $user->posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'content' => $post->content,
                    'image' => $post->image,
                    'goal' => $post->goal,
                    'status' => $post->status,
                    'echo_count' => $post->echoedUsers()->count(),
                    'karma_points' => 2450 // 固定
                ];
            }),
            'echoedPosts' => $user->echoedPosts()
                ->with('user')
                ->latest()
                ->get()
                ->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'content' => $post->content,
                        'image' => $post->image,
                        'goal' => $post->goal,
                        'status' => $post->status,
                        'echo_count' => $post->echoedUsers()->count(),
                        'karma_points' => 2450 // 固定
                    ];
                }),

                'waves_started' => $user->posts()->count(),
                'echoes_given' => $user->echoedPosts()->count(),
                'karma_points' => 2450,  // 固定
        ]);
    }
}
