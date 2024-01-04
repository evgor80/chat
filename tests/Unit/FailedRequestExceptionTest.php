<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Exceptions\FailedRequestException;
use Illuminate\Support\MessageBag;

class FailedRequestExceptionTest extends TestCase{
    public function test_that_be_thrown_with_given_values()
    {
        try {
            throw new FailedRequestException('Test message', new MessageBag(['test' => 'Error message']), 500);
        } catch (FailedRequestException $e) {
            $this->assertInstanceOf(FailedRequestException::class, $e);
            $this->assertSame('Test message', $e->error);
            $this->assertSame(500, $e->code);
            $this->assertSame('Error message', $e->errorMessages->toArray()['test'][0]);
        }
    }
}