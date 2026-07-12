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
        'stock',
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
        'stock' => 'integer',
        'status' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Whether the product currently has stock available.
     */
    public function getInStockAttribute(): bool
    {
        return (int) $this->stock > 0;
    }

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
    
    // Helper to get asset objects for the stored image IDs.
    // Memoized in the relations bucket: an accessor would otherwise run a
    // fresh query on EVERY access (product lists should batch this away
    // entirely with preloadAssets()).
    public function getAssetsAttribute()
    {
        if (!$this->relationLoaded('assets')) {
            $lookup = empty($this->images)
                ? collect()
                : Asset::whereIn('id', $this->images)->get()->keyBy('id');

            $this->setRelation('assets', $this->orderedAssets($lookup));
        }

        return $this->getRelation('assets');
    }

    public function getMainImageAttribute()
    {
        if (empty($this->images)) {
            return null;
        }

        // Served from the memoized assets collection: no query per access.
        return $this->assets->firstWhere('id', (int) $this->images[0]);
    }

    /**
     * Resolve the assets accessor for a whole product list with a single
     * query instead of one query per product. Pass a collection or array of
     * products (for paginators, pass ->getCollection()).
     */
    public static function preloadAssets($products): void
    {
        $ids = collect($products)->flatMap(fn ($product) => $product->images ?? [])->unique()->values();

        $lookup = $ids->isEmpty()
            ? collect()
            : Asset::whereIn('id', $ids)->get()->keyBy('id');

        foreach ($products as $product) {
            $product->setRelation('assets', $product->orderedAssets($lookup));
        }
    }

    /** The product's assets in the order of its images id array. */
    protected function orderedAssets($lookup)
    {
        return collect($this->images ?? [])
            ->map(fn ($id) => $lookup[(int) $id] ?? null)
            ->filter()
            ->values();
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
