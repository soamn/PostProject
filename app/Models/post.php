<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Post extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable=[
        'title',
        'description',
        'published_at',
        'show_title',
        'slug'
    ];
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }
}
