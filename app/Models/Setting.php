<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['key', 'value'];

    protected function casts(): array
    {
        return ['value' => 'array'];
    }

    /** Raw settings map, cached until the admin saves. */
    public static function all_values(): array
    {
        return Cache::rememberForever('cms.settings', fn () => rescue(
            fn () => static::query()->get()->mapWithKeys(fn (Setting $s) => [$s->key => $s->value])->all(),
            [],
            false
        ));
    }

    /** Setting in the current locale; falls back to the given default when unset or blank. */
    public static function get(string $key, mixed $default = null): mixed
    {
        $value = static::all_values()[$key] ?? null;
        if (is_array($value)) {
            $value = filled($value[app()->getLocale()] ?? null) ? $value[app()->getLocale()] : ($value['id'] ?? null);
        }

        return filled($value) ? $value : $default;
    }

    public static function flush(): void
    {
        Cache::forget('cms.settings');
    }
}
