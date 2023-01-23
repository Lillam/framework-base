<?php

namespace Vyui\Support\Helpers;

class Numerable
{
    /**
     * The number of which will be objectiied; and returned content upon this being cast to an integer or a float.
     *
     * @var int|float
     */
    protected int|float $number;

    /**
     * @param int|float $number
     */
    public function __construct(int|float $number)
    {
        $this->number = $number;
    }

    /**
     * Add a certain amount to the number that's stored against the object.
     *
     * @param int|float $number
     * @return $this
     */
    public function add(int|float $number): static
    {
        $this->number += $number;

        return $this;
    }

    /**
     * Subtract a certain amount from the number that's stored against the object.
     *
     * @param int|float $number
     * @return $this
     */
    public function subtract(int|float $number): static
    {
        $this->number -= $number;

        return $this;
    }

    /**
     * Multiply a certain amount to the number that's stored against the object.
     *
     * @param int|float $number
     * @return $this
     */
    public function multiply(int|float $number): static
    {
        $this->number *= $number;

        return $this;
    }

    /**
     * Divide a certain amount from the number that's stored against the object.
     *
     * @param int|float $number
     * @return $this
     */
    public function divide(int|float $number): static
    {
        $this->number /= $number;

        return $this;
    }

    /**
     * Return the number value as a float.
     *
     * @return float
     */
    public function toFloat(): float
    {
        return round(floatval($this->number), 10);
    }

    /**
     * Return the number value as a whole number integer.
     *
     * @return int
     */
    public function toInt(): int
    {
        return intval($this->number);
    }
}