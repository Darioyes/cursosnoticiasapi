<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'epigraph',
        'title',
        'slug',
        'image',
        'content',
        'featured',
        'visible',
        'category_news_id',
        'category_course_id'

    ];

    //relacion de uno a muchos con la tabla comments
    public function comments(){
        return $this->hasMany(Comments::class);
    }

    //relacion de uno a muchos con la tabla articles
    public function articles(){
        return $this->hasMany(Articles::class);
    }

    //relacion de muchos a uno con la tabla categories_news
    public function category_news(){
        return $this->belongsTo(Categories_news::class);
    }

    //relacion de muchos a uno con la tabla categories_courses
    public function category_course(){
        return $this->belongsTo(Categories_courses::class);
    }
}
