<?php

namespace Tests\Unit;

use Vyui\Tests\TestCase;

class RandomTest extends TestCase
{
    public function testSomethingReallyRandom(): void
    {
        $this->assertEquals(1 + 1, 3);
        $this->assertLooseEquals("1", 1);
        $this->assertTrue(true);
        $this->assertFalse(false);
        $this->assertCount(1, [2]);
        $this->assertNotEmpty([1, 2, 3]);
        $this->assertEmpty([]);
    }
}