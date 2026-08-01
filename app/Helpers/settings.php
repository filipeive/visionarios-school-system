<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    /**
     * Obter ou definir valor de configuração.
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key = null, $default = null)
    {
        if (is_null($key)) {
            return app(Setting::class);
        }

        try {
            $value = Cache::remember("setting_{$key}", 3600, function () use ($key) {
                $setting = Setting::where('key', $key)->first();
                return $setting ? $setting->value : null;
            });

            if ($value !== null && $value !== '') {
                return $value;
            }
        } catch (\Throwable $e) {
            // Em caso de falha de BD ou tabelas não criadas
        }

        return $default;
    }
}
