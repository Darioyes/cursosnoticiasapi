<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories_news extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    //relacion de uno a muchos con la tabla news
    public function news(){
        return $this->hasMany(News::class);
    }
}

