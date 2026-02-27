<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// app/Http/Controllers/ProductController.php

use App\Models\Product;
use App\Models\Category;
use Inertia\Inertia;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->get();

        $products = Product::with('category')
            ->where('status', '!=', 'soldout')
            ->latest()
            ->get();

        return Inertia::render('Market', [
            'categories' => $categories,
            'products' => $products
        ]);
    }
    // app/Http/Controllers/ProductController.php

    public function create()
    {
        return Inertia::render('Products/Create', [
            'categories' => Category::orderBy('sort_order')->get(),
            'posts' => Post::doesntHave('product')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'price' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'post_id' => 'nullable|exists:posts,id',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'brand' => $request->brand,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'post_id' => $request->post_id,
            'image' => $imagePath,
            'status' => 'launching'
        ]);

        return redirect()->route('market');
    }
}