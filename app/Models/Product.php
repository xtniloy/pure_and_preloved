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
        // 'category_id', // Removed column
        'images',
        'thumbnail_image_id',
        'meta_image_id',
        'status',
        'is_featured',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'status' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // New relationship
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    // Keep accessor for backward compatibility if needed, or update views.
    // Let's create an accessor that returns the first category to avoid breaking $product->category calls immediately
    public function getCategoryAttribute()
    {
        return $this->categories->first();
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

    public function thumbnailImage()
    {
        return $this->belongsTo(Asset::class, 'thumbnail_image_id');
    }

    public function metaImage()
    {
        return $this->belongsTo(Asset::class, 'meta_image_id');
    }
}
