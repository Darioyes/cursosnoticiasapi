<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article_image extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'location',
        'description',
    ];

    //relacion de uno a uno con la tabla articles
    public function articles(){
        return $this->belongsTo(Articles::class);
    }
}
