<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
    use HasFactory;

    protected $fillable = [
        'subtitle',
        'entrance',
        'body_news',
        'image',
        'news_id',
        'article_image_id'
    ];

    //relacion de muchos a uno con la tabla news
    public function news(){
        return $this->belongsTo(News::class);
    }

    //relacion de uno a uno con la tabla article_images
    public function article_images(){
        return $this->hasOne(Article_image::class);
    }
}
