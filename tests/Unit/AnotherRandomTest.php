<?php

namespace Tests\Unit;

use Vyui\Tests\TestCase;

class AnotherRandomTest extends TestCase
{
    public function test1IsGreaterThan0()
    {
        $this->assertGreaterThan(1, 0);
        $this->assertCount(1, [1, 2, 3]);
    }
}