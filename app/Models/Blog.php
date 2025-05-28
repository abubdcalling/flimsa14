<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'image',
        'details',
        'tags',
        'keywords',
        'meta_description',
        'meta_title',
        'publish',
    ];

    protected $casts = [
        'tags' => 'array',
        'keywords' => 'array',
        'publish' => 'boolean',
    ];
}
