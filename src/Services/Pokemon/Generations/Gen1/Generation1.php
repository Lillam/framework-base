<?php

namespace Vyui\Services\Pokemon\Generations\Gen1;

use Vyui\Services\Pokemon\Generation;

class Generation1 extends Generation
{
    /**
     * @var string
     */
    protected string $name = 'Red, Green, Blue and Yellow';

    /**
     * @var int
     */
    protected int $generationalPokemon = 151;

    /**
     * @var int
     */
    protected int $totalPokemon = 151;
}