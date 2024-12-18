<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $table = 'category';
    protected $fillable = ['name'];
    public $timestamps = false;

    public function category() {
        return $this->belongsTo(Category::class);
    }
}