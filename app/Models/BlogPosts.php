<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogPosts extends Model
{

    use HasFactory;
    
    protected $fillable = [
        'title',
        'body',
        'author_id',
        'user_id',
    ];
    public function author()
    {
        return $this->belongsTo(Authors::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
