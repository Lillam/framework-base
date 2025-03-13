<?php

namespace Vyui\Foundation\Console\Output;

class Colour
{
    protected array $printingColours = [
        ':info:'    => "\e[93m",
        ':error:'   => "\e[91m",
        ":success:" => "\e[92m",
        ":default:" => "\e[97m",
        ":end:"     => "\e[97m",
        // specific colouring options.
        ":green:"   => "\e[92m",
        ":red:"     => "\e[91m",
        ":orange:"  => "\e[93m",
        ":cyan:"    => "\e[96m",
        ":purple:"  => "\e[95m",
        ":blue:"    => "\e[94m",
        ":magenta:" => "\e[35m",
        ":yellow:"  => "\e[33m",
        ":black:"   => "\e[97m",
        ":white:"   => "\e[30m"
    ];

    public function getColours(): array
    {
        return $this->printingColours;
    }

    /**
     * Get the colour from the colour array, optionally can request it
     * via the full key :colour: or simply colour and the code will
     * attempt to find the actual colour key.
     *
     * @param string $colour
     * @return string
     */
    public function getColour(string $colour): string
    {
        if ($colour[0] !== ":") {
            $colour = ":$colour";
        }

        if ($colour[strlen($colour) - 1] !== ":") {
            $colour = "$colour:";
        }

        return $this->printingColours[$colour];
    }
}
