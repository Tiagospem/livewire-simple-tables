<?php

if (! function_exists('theme')) {
    function theme(array $theme, string $element): string
    {
        return strval(data_get($theme, $element));
    }
}
