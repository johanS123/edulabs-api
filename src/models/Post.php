<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {
    protected $table = 'posts';
    protected $fillable = ['title', 'content', 'userId', 'categoryId'];
    public $timestamps = false;

    public function user() {
        return $this->belongsTo(User::class, 'userId');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'categoryId');
    }
}
