<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Files\Models\Asset;

class HomeSection extends Model
{
    /**
     * Registry of the section types the homepage builder supports.
     *
     * - editable:  has an edit form in the admin panel
     * - deletable: can be removed from the page
     * - fixed:     pinned position (hero stays at the top, excluded from reordering)
     * - addable:   offered in the "Add Section" menu
     */
    public const TYPES = [
        'hero_slider' => [
            'label' => 'Hero Slider',
            'editable' => true,
            'deletable' => false,
            'fixed' => true,
            'addable' => false,
        ],
        'perks_strip' => [
            'label' => 'Perks Strip (static)',
            'editable' => false,
            'deletable' => true,
            'fixed' => false,
            'addable' => true,
        ],
        'module_grid' => [
            'label' => 'Image Banner Grid',
            'editable' => true,
            'deletable' => true,
            'fixed' => false,
            'addable' => true,
        ],
        'feature_strip' => [
            'label' => 'Image Feature Strip',
            'editable' => true,
            'deletable' => true,
            'fixed' => false,
            'addable' => true,
        ],
        'full_banner' => [
            'label' => 'Full-Width Banner',
            'editable' => true,
            'deletable' => true,
            'fixed' => false,
            'addable' => true,
        ],
        'featured_products' => [
            'label' => 'Featured Products Slider',
            'editable' => true,
            'deletable' => true,
            'fixed' => false,
            'addable' => true,
        ],
        'text_columns' => [
            'label' => 'Text Columns',
            'editable' => true,
            'deletable' => true,
            'fixed' => false,
            'addable' => true,
        ],
        'social_follow' => [
            'label' => 'Social Follow Strip',
            'editable' => false,
            'deletable' => true,
            'fixed' => false,
            'addable' => true,
        ],
    ];

    protected $fillable = [
        'type',
        'title',
        'data',
        'position',
        'is_active',
    ];

    protected $casts = [
        'data' => 'array',
        'is_active' => 'boolean',
    ];

    public function typeConfig(): array
    {
        return static::TYPES[$this->type] ?? [
            'label' => $this->type,
            'editable' => false,
            'deletable' => true,
            'fixed' => false,
            'addable' => false,
        ];
    }

    public function typeLabel(): string
    {
        return $this->typeConfig()['label'];
    }

    public function isEditable(): bool
    {
        return $this->typeConfig()['editable'];
    }

    public function isDeletable(): bool
    {
        return $this->typeConfig()['deletable'];
    }

    public function isFixed(): bool
    {
        return $this->typeConfig()['fixed'];
    }

    public static function addableTypes(): array
    {
        return array_filter(static::TYPES, fn (array $config) => $config['addable']);
    }

    /**
     * Inject an `image_src` next to every image_id / image_url pair inside the
     * sections' data payloads so views never have to resolve files themselves.
     * File-manager images are resolved with a single query.
     */
    public static function resolveImages($sections): void
    {
        $ids = [];

        foreach ($sections as $section) {
            self::walkImageNodes($section->data ?? [], function (array $node) use (&$ids) {
                if (!empty($node['image_id'])) {
                    $ids[] = (int) $node['image_id'];
                }
            });
        }

        $assets = empty($ids)
            ? collect()
            : Asset::query()->whereIn('id', array_unique($ids))->get()->keyBy('id');

        foreach ($sections as $section) {
            $data = $section->data ?? [];
            $section->setAttribute('data', self::injectImageSrc($data, $assets));
        }
    }

    private static function walkImageNodes(array $data, callable $callback): void
    {
        if (array_key_exists('image_id', $data) || array_key_exists('image_url', $data)) {
            $callback($data);
        }

        foreach ($data as $value) {
            if (is_array($value)) {
                self::walkImageNodes($value, $callback);
            }
        }
    }

    private static function injectImageSrc(array $data, $assets): array
    {
        if (array_key_exists('image_id', $data) || array_key_exists('image_url', $data)) {
            $data['image_src'] = null;

            if (!empty($data['image_id']) && isset($assets[(int) $data['image_id']])) {
                $data['image_src'] = $assets[(int) $data['image_id']]->public_url;
            } elseif (!empty($data['image_url'])) {
                $data['image_src'] = asset($data['image_url']);
            }
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::injectImageSrc($value, $assets);
            }
        }

        return $data;
    }
}
