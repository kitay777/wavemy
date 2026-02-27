<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryTheme;
use Inertia\Inertia;

class CategoryThemeController extends Controller
{
    //
    public function index()
    {
        $themes = CategoryTheme::latest()->get();
        return Inertia::render('Admin/CategoryThemes/Index', [
            'themes' => $themes,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/CategoryThemes/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'thumbnail' => 'required|image',
            'summary' => 'required|max:128',
            'bonus' => 'required|max:256',
            'fee' => 'required|max:128',
            'max_participants' => 'required|integer|min:1',
        ]);

        $path = $request->file('thumbnail')->store('thumbnails', 'public');

        CategoryTheme::create([
            'thumbnail_path' => $path,
            'summary' => $data['summary'],
            'bonus' => $data['bonus'],
            'fee' => $data['fee'],
            'max_participants' => $data['max_participants'],
        ]);

        return redirect()->route('category-themes.index');
    }

}
