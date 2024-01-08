<?php

namespace Tests\Unit;

use App\Utils\MessageSanitizer;
use Tests\TestCase;

class MessageSanitizerTest extends TestCase
{
    public function test_changes_emoji_symbol_to_img_tag()
    {
        $sanitized_message = MessageSanitizer::sanitize('😃');
        $this->assertSame('<img src="/dist/emojis/1.png" alt="😃" width="30" height="30" ></img>', $sanitized_message);
    }

    public function test_sanitizes_tags()
    {
        $sanitized_message = MessageSanitizer::sanitize('<script>alert("Hello!")</script>');
        $this->assertSame('&lt;script&gt;alert(&quot;Hello!&quot;)&lt;/script&gt;', $sanitized_message);
    }

}
