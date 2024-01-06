<?php

namespace Tests\Unit;

use App\Utils\SlugGenerator;
use Tests\TestCase;

class SlugGeneratorTest extends TestCase
{
    public function test_generates_slug()
    {
        $slug = SlugGenerator::generate('!!! Главный   чат !!!');
        $this->assertSame('glavnyj-chat', $slug);
    }
}
