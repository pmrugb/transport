<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Throwable;

#[Fillable(['key', 'value'])]
class Setting extends Model
{
    public static function get(string $key, mixed $default = null): mixed
    {
        // Settings are read on auth and admin screens frequently, so cache each key briefly.
        try {
            return Cache::remember("settings:{$key}", now()->addMinutes(10), function () use ($key, $default) {
                return static::query()->where('key', $key)->value('value') ?? $default;
            });
        } catch (Throwable) {
            return $default;
        }
    }

    public static function set(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value]
        );

        Cache::forget("settings:{$key}");
    }

    public static function getBool(string $key, bool $default = false): bool
    {
        return filter_var(static::get($key, $default ? '1' : '0'), FILTER_VALIDATE_BOOLEAN);
    }

    public static function loginCaptchaEnabled(): bool
    {
        return static::getBool('login_recaptcha_enabled', false);
    }
}
