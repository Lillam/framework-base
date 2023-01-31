<?php

namespace Tests\Unit\Factories\User;

use App\Models\User;
use Vyui\Tests\TestCase;

class UserFactoryTest extends TestCase
{
    public function testUserFactoryIsMade(): void
    {
        $this->assertNull(null);
        $this->assertNotNull([]);

        $user = new User();

        $this->assertInstanceOf($user, User::class);
    }
}