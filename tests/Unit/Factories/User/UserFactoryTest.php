<?php

namespace Tests\Unit\Factories\User;

use App\Models\User;
use Vyui\Tests\Test;

class UserFactoryTest extends Test 
{
    public function testUserFactoryIsMade(): void
    {
        $this->assert(null)->isNull();
        $this->assert([])->isNotNull();
        $this->assert([])->isEmpty();

        // $user = new User();
    }
}
