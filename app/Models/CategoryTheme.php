<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag',
        'thumbnail_path',
        'summary',
        'bonus',
        'fee',
        'max_participants',
        'created_by', // ← これ！
    ];
    public function participants()
    {
        return $this->belongsToMany(User::class, 'category_theme_user')->withTimestamps();
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function tweets()
    {
        return $this->hasMany(Tweet::class);
    }



}
