<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'color_id', 'size_id', 'price', 'special_price', 'start_date', 'end_date', 'quantity', 'sku'];
}
