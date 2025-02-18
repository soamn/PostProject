<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable=[
        'title',
        'description',
        'published_at',
        'slug'
    ];
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }
}
