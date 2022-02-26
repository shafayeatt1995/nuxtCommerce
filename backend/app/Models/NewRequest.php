<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewRequest extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'message'];
    protected $casts = ['status' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
