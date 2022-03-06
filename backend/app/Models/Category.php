<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];
    protected $casts = ['status' => 'boolean'];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function childCategories()
    {
        return $this->hasMany(ChildCategory::class);
    }

    public function sizes()
    {
        return $this->hasMany(Size::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class,);
    }
}
