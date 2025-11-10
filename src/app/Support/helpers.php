<?php

use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Vite;

/**
 * Laravel vite() helper fallback
 */
if (!function_exists('vite')) {
    function vite($asset)
    {
        return App::make(Vite::class)(is_array($asset) ? $asset : func_get_args());
    }
}
