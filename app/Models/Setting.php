<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, $default = null)
    {
        $setting = static::query()->where('key', $key)->first();

        return $setting && $setting->value !== null ? $setting->value : $default;
    }

    public static function getMany(array $keys): array
    {
        return static::query()
            ->whereIn('key', $keys)
            ->pluck('value', 'key')
            ->all();
    }

    public static function set(string $key, $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
