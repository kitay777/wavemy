<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tweet;
use App\Models\CategoryTheme;
use App\Models\ChatMessage;
use App\Events\MessageSent;
use App\Http\Controllers\ChatRoomViewController;
use App\Http\Controllers\ChatRoomController;
use App\Http\Controllers\Admin\CategoryThemeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LineLinkController;
use App\Http\Controllers\LineWebhookController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ExploreController;

Route::middleware(['auth'])->group(function () {
    Route::get('/explore', [ExploreController::class, 'index'])
        ->name('explore');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('products.create');

    Route::post('/products', [ProductController::class, 'store'])
        ->name('products.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/market', [ProductController::class, 'index'])
        ->name('market');
});

// 公開トップ
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// 管理画面ルート
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('category-themes', CategoryThemeController::class);
});

// ツイート一覧（公開）
Route::get('/tweets', function () {
    $auth = auth()->user();
    $userId = $auth?->id;

    $tweets = Tweet::with(['user', 'likes'])
        ->withCount([
            'likes as like_count' => fn($q) => $q->where('type', 'like'),
            'likes as dislike_count' => fn($q) => $q->where('type', 'dislike'),
        ])
        ->latest()
        ->get()
        ->map(fn($tweet) => [
            'id' => $tweet->id,
            'user_id' => $tweet->user->id,
            'text' => $tweet->text,
            'user_name' => $tweet->user->name,
            'user_photo' => $tweet->user->profile_photo_url,
            'created_at' => $tweet->created_at->format('Y-m-d H:i'),
            'like_count' => $tweet->like_count,
            'dislike_count' => $tweet->dislike_count,
            'my_reaction' => $tweet->likes->firstWhere('user_id', $userId)?->type,
        ]);

    $followingIds = $auth ? $auth->following()->pluck('users.id')->toArray() : [];

    return Inertia::render('Tweet/Index', [
        'tweets' => $tweets,
        'followingIds' => $followingIds,
    ]);
})->name('tweets.index');

// ログインユーザー専用ルート
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // ツイート投稿画面
    Route::get('/tweet', fn() => Inertia::render('Tweet/Create'))->name('tweet.create');

    // ツイート投稿処理
    Route::post('/tweet', function (Request $request) {
        $user = Auth::user();
        $text = $request->input('text');

        preg_match_all('/@([a-zA-Z0-9_\-]+)/', $text, $matches);
        $tags = $matches[1] ?? [];
        $categoryThemeId = null;

        foreach ($tags as $tag) {
            $fullTag = '@' . $tag;
            $category = CategoryTheme::firstOrCreate(
                ['tag' => $fullTag],
                [
                    'summary' => "$fullTag の自動生成カテゴリ",
                    'bonus' => '初期参加特典なし',
                    'fee' => '無料',
                    'max_participants' => 10000,
                    'thumbnail_path' => 'default.jpg',
                    'created_by' => $user->id,
                ]
            );

            if (!$categoryThemeId) {
                $categoryThemeId = $category->id;
            }

            $category->participants()->syncWithoutDetaching([$user->id]);
        }

        Tweet::create([
            'user_id' => $user->id,
            'category_theme_id' => $categoryThemeId,
            'text' => $text,
        ]);

        return redirect()->route('dashboard')->with('success', 'ツイートしました！');
    })->name('tweet.store');

    // フォロー・アンフォロー
    Route::post('/follow/{user}', function (User $user) {
        $authUser = Auth::user();
        if ($user->id !== $authUser->id) {
            $user->followers()->syncWithoutDetaching([$authUser->id]);
        }
        return back();
    })->name('user.follow');

    Route::post('/unfollow/{user}', function (User $user) {
        $authUser = Auth::user();
        if ($user->id !== $authUser->id) {
            $user->followers()->detach($authUser->id);
        }
        return back();
    })->name('user.unfollow');

    // リアクション（いいね / うーん）
    Route::post('/tweets/{tweet}/react', function (Request $request, Tweet $tweet) {
        $user = Auth::user();
        $type = $request->input('type');
        if (!in_array($type, ['like', 'dislike'])) {
            abort(400);
        }
        $tweet->likes()->updateOrCreate(
            ['user_id' => $user->id],
            ['type' => $type]
        );
        return back();
    })->name('tweets.react');

    // カテゴリ参加
    Route::post('/category-themes/{id}/join', function ($id) {
        $theme = CategoryTheme::findOrFail($id);
        $theme->participants()->syncWithoutDetaching([Auth::id()]);
        return back();
    })->name('category-themes.join');

    // ダッシュボード
    // routes/web.php


    Route::middleware(['auth'])->group(function () {

        Route::get('/dashboard', [PostController::class, 'dashboard'])
            ->name('dashboard');

        Route::post('/posts/{post}/echo', [PostController::class, 'echo'])
            ->name('posts.echo');
    });


    Route::get('/dashboard.old', function () {
        $userId = auth()->id();
        $categories = CategoryTheme::with(['creator', 'tweets.user'])
            ->withCount('participants')
            ->latest()
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'thumbnail_path' => $cat->thumbnail_path,
                    'summary' => $cat->summary,
                    'bonus' => $cat->bonus,
                    'fee' => $cat->fee,
                    'max' => $cat->max_participants,
                    'tag' => $cat->tag,
                    'creator_name' => optional($cat->creator)->name ?? '不明',
                    'current' => $cat->participants_count,
                    'tweets' => $cat->tweets->map(fn($tweet) => [
                        'id' => $tweet->id,
                        'text' => $tweet->text,
                        'user_name' => $tweet->user->name,
                        'user_photo' => $tweet->user->profile_photo_url,
                        'created_at' => $tweet->created_at->format('Y-m-d H:i'),
                    ]),
                ];
            });


        $joinedIds = auth()->user()->joinedCategories()->pluck('category_theme_id')->toArray();

        $tweets = Tweet::with(['user', 'likes'])
            ->withCount([
                'likes as like_count' => fn($q) => $q->where('type', 'like'),
                'likes as dislike_count' => fn($q) => $q->where('type', 'dislike'),
            ])
            ->latest()->take(20)->get()
            ->map(fn($tweet) => [
                'id' => $tweet->id,
                'user_id' => $tweet->user->id,
                'text' => $tweet->text,
                'user_name' => $tweet->user->name,
                'user_photo' => $tweet->user->profile_photo_url,
                'created_at' => $tweet->created_at->format('Y-m-d H:i'),
                'like_count' => $tweet->like_count,
                'dislike_count' => $tweet->dislike_count,
                'my_reaction' => $tweet->likes->firstWhere('user_id', $userId)?->type,
            ]);

        return Inertia::render('Dashboard', [
            'categories' => $categories,
            'joinedCategoryIds' => $joinedIds,
            'tweets' => $tweets,
        ]);
    })->name('dashboard.old');

    // チャット機能
    Route::get('/chat/{friend}', function (User $friend) {
        return Inertia::render('ChatFriend', [
            'friend' => $friend,
            'user' => auth()->user(),
        ]);
    })->name('chat.friend');

    Route::get('/messages/{friend}', function (User $friend) {
        return ChatMessage::query()
            ->where(function ($q) use ($friend) {
                $q->where('sender_id', auth()->id())
                    ->where('receiver_id', $friend->id);
            })
            ->orWhere(function ($q) use ($friend) {
                $q->where('sender_id', $friend->id)
                    ->where('receiver_id', auth()->id());
            })
            ->with(['sender', 'receiver'])
            ->orderBy('id')
            ->get();
    });

    Route::post('/messages/{friend}', function (User $friend) {
        $message = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $friend->id,
            'text' => request('message'),
        ]);
        broadcast(new MessageSent($message))->toOthers();
        return $message;
    });

    Route::get('/chat-rooms/{room}', [ChatRoomViewController::class, 'show'])
        ->name('chat-rooms.show');
    Route::get('/chat-rooms/{room}/messages', [ChatRoomController::class, 'index']);
    Route::post('/chat-rooms/{room}/messages', [ChatRoomController::class, 'store']);
});
