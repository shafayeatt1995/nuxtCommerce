<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'sub_category_id', 'child_category_id', 'brand_id', 'name', 'features', 'description', 'items', 'specifications', 'weight', 'status'];
    protected $casts = ['status' => 'boolean', 'pending' => 'boolean', 'suspend' => 'boolean', 'stock' => 'boolean'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function childCategory()
    {
        return $this->belongsTo(ChildCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variations()
    {
        return $this->hasMany(Variation::class,);
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'material_product');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }
}
