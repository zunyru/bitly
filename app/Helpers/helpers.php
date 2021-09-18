<?php

use Illuminate\Support\Str;

if (!function_exists('generateSlug')) {

    function generateSlug($str): ?string
    {
        $slug = '';
        $slug = Str::of($str)->trim();
        $slug = preg_replace('@[\s!:;_\?=\\\+\*/%&#]+@', '-', $slug);
        //$slug = Str::lower($slug);

        return $slug;
    }
}

if (!function_exists('get_icon')) {
    function get_icon($icon_class)
    {
        if (isset($icon_class) && $icon_class == true) {
            $icons = explode("-", $icon_class);
            switch ($icons[0]) {
                case 'voyager':
                    $icon = '<span class="icon ' . $icon_class . '"></span>';
                    break;
                default:
                    $icon = '<img class="svg" src="' . asset($icon_class) . '">';
                    break;
            }
            return $icon;
        }
        return null;
    }
}
