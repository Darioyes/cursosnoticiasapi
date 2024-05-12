<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticlesFront extends Model
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
        return $this->belongsTo(NewsFront::class);
    }

}
