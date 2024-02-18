<?php

namespace Vyui\Support\Helpers;

class _Number
{
    /**
     * Create an object instantiation of the number that the user would like to be utilising. So that we can work
     * with a particular number in an object-oriented fashion.
     *
     * @param int|float $number
     * @return Numerable
     */
    public static function fromNumber(int|float $number): Numerable
    {
        return new Numerable($number);
    }
}
