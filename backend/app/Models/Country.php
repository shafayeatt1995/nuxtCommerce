<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];
    protected $casts = ['status' => 'boolean'];

    public function states()
    {
        return $this->hasMany(State::class);
    }

    // public function childCategories()
    // {
    //     return $this->hasMany(ChildCategory::class);
    // }

    // public function sizes()
    // {
    //     return $this->hasMany(Size::class);
    // }
}
