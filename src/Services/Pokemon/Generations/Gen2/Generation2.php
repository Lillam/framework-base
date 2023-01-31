<?php

namespace Vyui\Services\Pokemon\Generations\Gen2;

use Vyui\Services\Pokemon\Generation;

class Generation2 extends Generation
{
    /**
     * @var string
     */
    protected string $name = 'Gold, Silver and Crystal';

    /**
     * @var int
     */
    protected int $generationalPokemon = 100;

    /**
     * @var int
     */
    protected int $totalPokemon = 251;
}