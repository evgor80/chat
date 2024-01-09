<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class SpaControllerTest extends TestCase
{
    public function test_returns_spa(): void
    {
        $response = $this->get('/');
        $response->assertOk();
        $response->assertViewIs('spa');
    }

    public function test_returns_spa_for_any_url_requested(): void
    {
        $response = $this->get('/login');
        $response->assertOk();
        $response->assertViewIs('spa');
    }
}