<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'ar_name',
        'en_name',
    ];

    /**
     * Get all products in this category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
