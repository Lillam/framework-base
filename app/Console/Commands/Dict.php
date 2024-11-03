<?php

namespace App\Console\Commands;

use Vyui\Dictionary\Dictionary;
use Vyui\Foundation\Application;
use Vyui\Foundation\Console\Commands\Command;
use Vyui\Services\Filesystem\Filesystem;

class Dict extends Command
{
    protected Application $application;
    protected Filesystem $filesystem;
    protected Dictionary $dictionary;

    protected string $action;
    protected string $anagram;
    protected int $maxLength = 7;
    protected int $minLength = 3;

    public function __construct(Application $application, Filesystem $filesystem, array $arguments = [])
    {
        $this->application = $application;
        $this->filesystem = $filesystem;
        $this->dictionary = (new Dictionary($filesystem))->load();

        parent::__construct($application, $arguments);

        $this->setAction($this->arguments[0] ?? "anagram")
             ->setAnagram($this->arguments[1] ?? "")
             ->setMinLength($this->arguments[2] ?? 3)
             ->setMaxLength($this->arguments[3] ?? 7);
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
        $this->dictionary->setAnagram($this->anagram)
                         ->setAnagramMin($this->minLength)
                         ->setAnagramMax($this->maxLength);

        $words = $this->dictionary->findWordsFromAnagram();

        foreach ($words as $word) {
            $this->output->print($word);
        }

        return 1;
    }
}
