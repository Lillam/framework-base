<?php

namespace App\Console\Commands;

use Vyui\Dictionary\Dictionary;
use Vyui\Foundation\Application;
use Vyui\Foundation\Console\Commands\Command;

class Dict extends Command
{
    protected Application $application;
    protected Dictionary $dictionary;

    protected string $action;
    protected string $anagram;
    protected int $maxLength = 7;
    protected int $minLength = 3;

    public function __construct(Application $application, Dictionary $dictionary, array $arguments = [])
    {
        $this->application = $application;
        $this->dictionary = $dictionary;
        parent::__construct($application, $arguments);

        foreach ($arguments as $argument) {
            match (substr($argument, 0, 2)) {
                "an" => $this->setAction("anagram"),
                "wo" => $this->setAnagram(str_replace(["word=", "wo="], "", $argument)),
                "mi" => $this->setMinLength((int) str_replace(["min=", "mi="], "", $argument)),
                "ma" => $this->setMaxLength((int) str_replace(["max=", "ma="], "", $argument)),
                default => null
            };
        }
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function setAnagram(string $anagram): self
    {
        $this->anagram = $anagram;

        return $this;
    }

    public function setMaxLength(int $length): self
    {
        $this->maxLength = $length;

        return $this;
    }

    public function setMinLength(int $length): self
    {
        $this->minLength = $length;

        return $this;
    }

    public function execute(): int
    {
        if (! isset($this->anagram)) {
            return $this->output->printColour("missing arguments", "red") ?? 0;
        }

        $this->dictionary->setAnagram($this->anagram)
                         ->setAnagramMin($this->minLength)
                         ->setAnagramMax($this->maxLength);

        $words = $this->dictionary->findWordsFromAnagram();

        usort($words, function (string $a, string $b) {
            return strlen($a) - strlen($b);
        });

        foreach ($words as $word) {
            match (strlen($word)) {
                3 => $this->output->printColour("3[$word]", "cyan"),
                4 => $this->output->printColour("4[$word]", "green"),
                5 => $this->output->printColour("5[$word]", "orange"),
                6 => $this->output->printColour("6[$word]", "magenta"),
                7 => $this->output->printColour("7[$word]", "blue"),
                default => $this->output->printColour($word, "white")
            };
        }

        return 1;
    }
}