<?php

declare(strict_types=1);

use TiagoSpem\SimpleTables\Services\StubService;

it('returns the correct stub path', function (): void {
    $stubService  = new StubService();
    $stubFileName = 'example.stub';
    $expectedPath = realpath(__DIR__ . '/../../resources/stubs/' . $stubFileName);

    expect(realpath($stubService->getStubPath($stubFileName)))->toBe($expectedPath);
});

it('returns the correct stub content', function (): void {
    $stubService     = new StubService();
    $stubPath        = base_path('resources/stubs/example.stub');
    $expectedContent = 'This is a stub content.';

    File::ensureDirectoryExists(dirname($stubPath));
    File::put($stubPath, $expectedContent);

    expect($stubService->getStubContent($stubPath))->toBe($expectedContent);

    File::delete($stubPath);
});
