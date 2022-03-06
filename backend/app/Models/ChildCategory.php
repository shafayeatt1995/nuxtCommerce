<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildCategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'sub_category_id', 'name', 'status'];
    protected $casts = ['status' => 'boolean'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
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
