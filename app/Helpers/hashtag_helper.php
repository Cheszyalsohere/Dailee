<?php
if (!function_exists('parse_hashtags')) {
    function parse_hashtags($content) {
        return preg_replace(
            '/#(\w+)/u',
            '<a href="' . base_url('hashtag/$1') . '" class="text-persianblue dark:text-petalfrost font-bold hover:underline">#$1</a>',
            esc($content)
        );
    }
}