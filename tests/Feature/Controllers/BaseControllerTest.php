<?php

namespace Tests\Feature\Controllers;

use App\Http\Controllers\API\BaseController;
use Tests\TestCase;

class BaseControllerTest extends TestCase
{
    public function test_that_sends_response(){
        $controller = new BaseController();
        $response = $controller->sendResponse(['test'=> 'test data'], 'Test response');
        $this->assertEquals(200, $response->status());
        $body = $response->getData(true);
        $this->assertEquals('Test response', $body['message']);
        $this->assertEquals('test data', $body['data']['test']);

    }
}