<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    /** @use HasFactory<\Database\Factories\TodoFactory> */
    use HasFactory;
    protected $fillable = [
        'title',
        'user_id',
        'completed',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
