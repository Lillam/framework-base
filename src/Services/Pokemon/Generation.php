<?php

namespace Vyui\Services\Pokemon;

class Generation
{
    /**
     * The name of the title of game that it is.
     *
     * @var string
     */
    protected string $name = '';

    /**
     * The region in which the game has been based in.
     *
     * @var string
     */
    protected string $region = '';

    /**
     * The total amount of pokemon that had been introduced within the generation
     *
     * @var int
     */
    protected int $generationalPokemon = 0;

    /**
     * The total amount of Pokemon that exists up to this point of generation.
     *
     * @var int
     */
    protected int $totalPokemon = 0;

    /**
     * Get the title of the generation.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the region that the game was based.
     *
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @return int
     */
    public function getGenerationalPokemon(): int
    {
        return $this->generationalPokemon;
    }

    /**
     * @return int
     */
    public function getTotalPokemon(): int
    {
        return $this->totalPokemon;
    }
}