<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Files\Models\Asset;

class Category extends Model
{
    use HasFactory;

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
