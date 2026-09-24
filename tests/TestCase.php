<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // The suite never talks to OpenRouter. Without this, any test that posts
        // to /create bills a real generation — slow, non-deterministic, and it
        // quietly spends the account's quota. Blueprint generation therefore
        // falls back to the deterministic template, and OpenRouterGeneratorTest
        // sets its own key and fakes the responses.
        config(['services.openrouter.key' => null]);

        // Anything that still tries to reach the network fails loudly.
        Http::preventStrayRequests();
    }
}
