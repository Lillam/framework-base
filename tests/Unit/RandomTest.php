<?php

namespace Tests\Unit;

use Vyui\Tests\Test;

class RandomTest extends Test
{
    public function testing(): void
    {
        $basket = [1];
        $this->assert(800)->equals(total($basket));
        $basket = [1, 2, 3, 4, 5];
        $this->assert(3000)->equals(total($basket));
        $basket = [1, 1, 2, 2, 3, 3, 3, 1, 1, 1, 4];
        // 4, 3, 2, 1, 1
        // 4 * 800 * 0.8 = 2560
        // 3 * 800 * 0.9 = 2160
        // 2 * 800 * 0.95 = 1520
        // 800
        // 800
        // 2560 + 2160 + 1520 + 800 + 800 = 7840
        $this->assert(7840)->equals(total($basket));
        $basket = [1, 1, 2, 2, 3, 3, 4, 5];
        $this->assert(5120)->equals(total($basket));
    }

    // public function testIsOneEqualToOne(): void
    // {
    //     $this->assert("hello")->equals("hello")->description("Does hello equal hello?");
    //     $this->assert(false)->equals(false);
    //     $this->assert(true)->equals(true);
    //     $this->assert(10)->equals(10);
    //     $this->assert(10)->equals(10);
    //     $this->assert(10)->equals(10);
    //     $this->assert(10)->equals(10);
    //     $this->assert(10)->equals(10);
    //     $this->assert(10)->equals(10);
    //     $this->assert([1])->equals([1]);
    // }

    // public function testIsOneEqualToTwo(): void
    // {
    //     $this->assert(1)->equals(1);
    // }
}

function total(array $items): int {
    $price = 0;

    foreach (extractBasket($items) as $basket) {
        $price += match (count($basket)) {
            1 => (1 * 800),
            2 => (2 * 800) * 0.95,
            3 => (3 * 800) * 0.90,
            4 => (4 * 800) * 0.80,
            5 => (5 * 800) * 0.75,
            default => 0,
        };
    }

    return (int) round($price);
}

function extractBasket(array $items): array {
    if (count($items) === 0) {
        return [];
    }

    $groups = array_values(array_count_values($items));
    $basket = array_fill(0, max($groups), []);
    $groupIndex = 0;
    $currentBasketIndex = 0;

    while (($groups[$groupIndex] ?? 0) > 0) {
        if (isset($groups[$groupIndex])) {
            $basket[$currentBasketIndex][] = 'item';
            $currentBasketIndex += 1;
            $groups[$groupIndex] -= 1;
        }

        if ($groups[$groupIndex] === 0) {
            $groupIndex++;
            $currentBasketIndex = 0;
        }
    }

    return $basket;
}
