<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentsFront extends Model
{
    use HasFactory;
    protected $fillable = [
        'comment',
        'news_id'
    ];

    //relacion de muchos a uno con la tabla news
    public function news(){
        return $this->belongsTo(NewsFront::class);
    }
}
