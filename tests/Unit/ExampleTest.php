<?php

test('true is true', function () {
    expect(true)->toBeTrue();
});

test('false is false', function () {
    expect(false)->toBeFalse();
});

test('integers can be compared', function () {
    expect(1 + 1)->toBe(2);
});

test('strings can be compared', function () {
    expect('compost')->toBe('compost');
});

test('arrays can contain values', function () {
    expect(['admin', 'aprendiz'])->toContain('aprendiz');
});
