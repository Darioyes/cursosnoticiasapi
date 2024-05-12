<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactFront extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'message',
        'file',
    ];
}
