<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

/**
 * Cached shop sidebar filter data: the category tree per gender plus the
 * global condition / tag / price-range facets (six queries per shop view
 * otherwise). Product and category admin changes must call clear().
 */
class ShopCache
{
    public const KEY_PREFIX = 'shop:filters:';
    public const GENDERS = ['women', 'man', 'unisex'];

    public static function filters(string $gender): array
    {
        $gender = in_array($gender, self::GENDERS, true) ? $gender : 'women';

        return Cache::remember(self::KEY_PREFIX . $gender, now()->addHours(6), function () use ($gender) {
            $materials = Product::where('status', true)->whereNotNull('material')->distinct()->pluck('material');
            $carats = Product::where('status', true)->whereNotNull('carat')->distinct()->pluck('carat');

            return [
                'categories' => Category::where('status', true)
                    ->where('gender', $gender)
                    ->whereNull('parent_id')
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('id', 'asc')
                    ->with(['children' => function ($query) {
                        $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
                    }])
                    ->get(),
                'conditions' => Product::where('status', true)->whereNotNull('condition')->distinct()->pluck('condition'),
                'tags' => $materials->merge($carats)->unique()->values(),
                'min_price' => Product::where('status', true)->min('price') ?? 0,
                'max_price' => Product::where('status', true)->max('price') ?? 10000,
            ];
        });
    }

    public static function clear(): void
    {
        foreach (self::GENDERS as $gender) {
            Cache::forget(self::KEY_PREFIX . $gender);
        }
    }
}
