<?php

namespace App\Support;

use App\Models\CompanySetting;
use Illuminate\Support\Facades\Cache;

class AppSettings
{
    protected const CACHE_KEY = 'app_company_setting';
    protected const CACHE_TTL = 300; // seconds

    public static function company(): ?CompanySetting
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, static fn () => CompanySetting::first());
    }

    public static function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
