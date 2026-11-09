<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Trade\Models\Charts\Candle;
use Modules\Setting\Models\Setting;

if (!function_exists('getSettings')) {
    /**
     * use this function to get values form setting table in blade
     */
    function getSettings(string $settingName, string $default = ''): string
    {
        $setting = Cache::rememberForever(
            'settings',
            static function () {
                return Setting::all();
            }
        );

        $setting = $setting->where('name', $settingName)->first();

        return $setting
            ? $setting->value
            : $default;
    }
}

if (!function_exists('getCategoryContent')) {
    /**
     * get content of category according to slug
     */
    function getCategoryContent(Collection $categories, ?string $slug = null, ?int $id = null): array
    {
        $activeCategories = $categories->where('status', 1);
        if (!$slug && !$id) {
            return [];
        }

        $categories = !empty($slug)
            ? $activeCategories->where('slug', $slug)->first()
            : $activeCategories->where('id', $id)->first();

        if (!$categories) {
            return [];
        }

        return $categories->contents;
    }
}

if (!function_exists('modules_path')) {
    function modules_path(string $path = ''): string
    {
        return base_path('Modules') . (!empty($path) ? "/$path" : '');
    }
}
