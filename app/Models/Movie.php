<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'genre',
        'rating',
        'release_year',
        'category_id',
    ];

    /**
     * Relasi ke kategori film.
     */
    public function category()
    {
        return $this->belongsTo(MovieCategory::class, 'category_id');
    }

    /**
     * Atribut mutator untuk kelas rating.
     */
    public function getRatingClassAttribute()
    {
        if ($this->rating >= 8.5) {
            return 'Top Rated';
        }
        if ($this->rating >= 7.0) {
            return 'Popular';
        }
        return 'Regular';
    }
}