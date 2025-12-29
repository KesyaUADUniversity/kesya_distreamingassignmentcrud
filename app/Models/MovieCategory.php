<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieCategory extends Model
{
    use HasFactory;

    protected $table = 'movie_categories'; // ✅ Ganti ke plural

    protected $fillable = [
        'name',
        'description',
    ];

    public function movies()
    {
        return $this->hasMany(Movie::class, 'category_id');
    }
}