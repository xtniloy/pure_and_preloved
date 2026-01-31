<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Files\Models\Asset;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'sale_price',
        'condition',
        'material',
        'weight',
        'carat',
        'category_id',
        'images',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    // Helper to get asset objects for the stored image IDs
    public function getAssetsAttribute()
    {
        if (empty($this->images)) {
            return collect([]);
        }
        // Assuming images stored as array of asset IDs
        return Asset::whereIn('id', $this->images)->get();
    }
    
    public function getMainImageAttribute()
    {
        if (empty($this->images)) {
            return null;
        }
        $firstId = $this->images[0];
        return Asset::find($firstId);
    }
}
