<?php

namespace TiagoSpem\SimpleTables\Themes;

interface ThemeInterface
{
    /**
     * @return array<string, string|array<string, string>>
     */
    public function getStyles(): array;
}
