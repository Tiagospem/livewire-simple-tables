<?php

declare(strict_types=1);

use TiagoSpem\SimpleTables\Tests\TestCase;

uses(TestCase::class)->in('Feature', 'Unit');

if ( ! function_exists('mb_trim')) {
    function mb_trim(string $str): string
    {
        return mb_trim($str);
    }
}
