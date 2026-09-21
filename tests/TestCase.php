<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Tests never need built assets, so a stale or missing Vite manifest
        // after switching lesson tags cannot fail them.
        $this->withoutVite();
    }
}
