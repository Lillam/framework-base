<?php

namespace Vyui\Services\Pokemon;

abstract class Pokemon
{
    /**
     * The name of the Pokemon
     *
     * @var string
     */
    protected string $name = '';

    /**
     * The key of the Pokemon that would reside in the Pokedex
     *
     * @var int
     */
    protected int $key = 0;


    /**
     * The types that the Pokemon has
     *
     * @var Type[]
     */
    protected array $types = [];
}