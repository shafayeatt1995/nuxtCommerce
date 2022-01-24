<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'duration_name', 'duration_day', 'price', 'discount_price', 'discount_start', 'discount_end', 'product_limit', 'storage_limit', 'variation_limit', 'inventory', 'pos', 'support', 'qr_code', 'status'];

    protected $casts = [
        'inventory' => 'boolean',
        'pos' => 'boolean',
        'support' => 'boolean',
        'qr_code' => 'boolean',
        'status' => 'boolean',
    ];
}
