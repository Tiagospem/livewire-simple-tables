<?php

use TiagoSpem\SimpleTables\Tests\TestCase;

uses(TestCase::class)->in('Feature', 'Unit');

if (! function_exists('mb_trim')) {
    function mb_trim(string $str): string
    {
        return trim($str);
    }
}
