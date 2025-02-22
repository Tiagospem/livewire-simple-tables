<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Services;

class StubService
{
    public function getStubPath(string $stubFileName): string
    {
        return __DIR__ . '/../../resources/stubs/' . $stubFileName;
    }

    public function getStubContent(string $stubPath): string
    {
        return file_get_contents($stubPath) ?: '';
    }
}
