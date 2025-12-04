<?php

if (!function_exists('proxied_image_url')) {
    function proxied_image_url(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        return route('image.proxy', ['token' => encrypt($url)]);
    }
}
