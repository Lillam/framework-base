<?php

namespace Tests\Feature;

use Vyui\Tests\TestCase;
use App\Http\Controllers\Api\UserController;

class ControllerTest extends TestCase
{
    public function testControllerInstantiated(): void
    {
        $controller = app()->make(UserController::class);
        $this->assertInstanceOf($controller, UserController::class);
    }
}