<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStats extends Model
{
    use HasFactory;
    protected $table = "stats";
    protected $fillable = [
        "product",
        "product_id",
        "qty_received"
    ];
    public function products()
    {
        return  $this->hasOne(Products::class);
    }
}
