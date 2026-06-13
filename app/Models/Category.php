<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Files\Models\Asset;

class Category extends Model
{
    use HasFactory;

    /**
     * Maximum category nesting depth. Gender (e.g. Women) is the top level
     * chosen on the landing page, so two category levels sit under it:
     * Women(gender) > Jewellery(depth 1) > Rings(depth 2).
     */
    public const MAX_DEPTH = 2;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'gender',
        'asset_id',
        'status',
        'sort_order',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Ancestor chain from the top-level root down to this category's parent,
     * used to build the drill-down breadcrumb.
     */
    /**
     * This category's level in the tree: 1 for a root (no parent),
     * 2 for its children, 3 for grandchildren.
     */
    public function depth(): int
    {
        return $this->ancestors()->count() + 1;
    }

    /** Whether a child can still be nested under this category. */
    public function canHaveChildren(): bool
    {
        return $this->depth() < self::MAX_DEPTH;
    }

    public function ancestors()
    {
        $chain = collect();
        $node = $this->parent;

        while ($node) {
            $chain->prepend($node);
            $node = $node->parent;
        }

        return $chain;
    }
}
