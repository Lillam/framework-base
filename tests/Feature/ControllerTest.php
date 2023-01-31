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
        $this->assertArrayHasKey('test', ['test' => true]);
        $this->assertArrayHasNotKey('test', ['nottest' => false]);
    }

    public function testThatMultipleTestsGetRun(): void
    {
        /** @var UserController $controller */
        $controller = app()->make(UserController::class);
        $this->assertNotNull($controller);
    }
}