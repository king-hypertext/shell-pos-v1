<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStats extends Model
{
    use HasFactory;
    protected $table = "stats";
    public function products()
    {
        return  $this->hasOne(Products::class);
    }
}
