<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesCoursesFront extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
    ];

    //relacion de uno a muchos con la tabla news
    public function courses(){
        return $this->hasMany(NewsFront::class);
    }

}