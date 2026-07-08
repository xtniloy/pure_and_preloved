<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Files\Models\Asset;

class BlogPost extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_PRIVATE = 'private';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'featured_image_id',
        'meta_image_id',
        'admin_id',
        'status',
        'allow_comments',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'allow_comments' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * All post statuses with a human label (for admin selects/badges).
     *
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_PRIVATE => 'Private',
        ];
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(BlogCategory::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class);
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'featured_image_id');
    }

    public function metaImage(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'meta_image_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Posts visible on the public site: published AND past their publish date
     * (a future published_at acts as a scheduled post).
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Date shown on the public site.
     */
    public function getDisplayDateAttribute(): \Illuminate\Support\Carbon
    {
        return $this->published_at ?? $this->created_at;
    }
}
