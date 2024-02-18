<?php

namespace Tests\Unit;

use Vyui\Tests\TestCase;

class AnotherRandomTest extends TestCase
{
    public function test1IsGreaterThan0(): void
    {
        $this->assertGreaterThan(1, 0);
        $this->assertCount(3, [1, 2, 3]);
    }
}
