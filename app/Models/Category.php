<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    //protected $table = 'category';

    protected $fillable = [
        'title',
        'image',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}