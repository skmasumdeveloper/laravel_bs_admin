<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_published',
    ];

    /**
     * Use slug for route model binding (if used)
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
