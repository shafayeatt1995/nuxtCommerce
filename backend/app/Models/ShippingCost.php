<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCost extends Model
{
    use HasFactory;

    public function shpippingCostRule()
    {
        return $this->hasOne(ShippingCostRule::class);
    }
}
