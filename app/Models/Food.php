<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    public function getDescriptionAttribute($value)
    {
        return trim($value);
    }

    public function getTimeAttribute($value)
    {
        return $value <= 1 ? $value . ' minute' : $value . ' minutes';
    }
}
