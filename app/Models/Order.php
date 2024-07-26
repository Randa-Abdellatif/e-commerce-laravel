<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable =[
        'status',
        'user_id',
        'total_price',
        'phone',
        'address',

    ];

    public function user(){
      // return $this->belongsTo(User::class, 'user_id');
        return $this->hasMany(User::class,  'user_id');
    }

    public function items(){
       return $this->hasMany(OrderItems::class);
    }
}
