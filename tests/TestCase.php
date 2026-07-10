<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Avoid requiring public/build/manifest.json during HTTP feature tests
        $this->withoutVite();
    }
}
