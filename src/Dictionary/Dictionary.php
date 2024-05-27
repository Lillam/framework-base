<?php

namespace Vyui\Dictionary;

use Vyui\Dictionary\Concerns\Anagrams;
use Vyui\Dictionary\Concerns\Conversion;
use Vyui\Services\Filesystem\FilesystemContract as Filesystem;

class Dictionary
{
    use Anagrams, Conversion;

    /**
     * The file system that the dictionary will be utilising in order for acquiring the files of the dictionary.
     *
     * @var Filesystem
     */
    protected FileSystem $filesystem;

    /**
     * path of all the dictionary files.
     *
     * @var string
     */
    protected string $path = __DIR__ . '/words';

    /**
     * The words of which are loaded within the dictionary.
     *
     * @var array
     */
    protected array $words = [];

    /**
     * an array of the words that have been added to the
     *
     * @var string[]
     */
    protected array $wordsAdded = [];

    /**
     * The total number of words that reside in the dictionary.
     *
     * @var int
     */
    protected int $wordsInDictionary = 0;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(FileSystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Load all the words in the dictionary and store it against the object. An efficiency gain so that the words don't
     * need to be re-loaded from the file.
     *
     * @return $this
     */
    public function load($associatively = false): static
    {
        foreach ($this->filesystem->files($this->getPath("/text")) as $file) {
            array_map(function (string $line) use ($associatively) {
                // if the line happens to be an empty string then we're not going to care for its' existence and just
                // simply skip past it and not append it anywhere.
                if (! $line) {
                    return;
                }

                $associatively
                    ? $this->words[$line[0]][] = $line
                    : $this->words[]           = $line;
            }, $this->filesystem->lines($file));
        }

        return $this;
    }

    /**
     * Add a word to the dictionary, however; only add the word if the word didn't exist in the dictionary's word set
     * already.
     *
     * @param string $word
     * @return $this
     */
    public function addWord(string $word): static
    {
        if (! in_array($word, $this->words)) {
            $this->wordsAdded[] = $word;
            $this->words[] = $word;
        }

        return $this;
    }

    /**
     * Add words to the dictionary; however only add the singular words if the word didn't already exist within the
     * dictionary's word set.
     *
     * @param string[] $words
     * @return $this
     */
    public function addWords(array $words): static
    {
        foreach ($words as $word) {
            $this->addWord($word);
        }

        return $this;
    }

    /**
     * Add words to the dictionary from a string, this will take a string of words and explode the string into an array
     * defaultly separating via space characters; however giving the support for the user to explode on whatever they
     * would like.
     *
     * @param string $words
     * @param string $separator
     * @return $this
     */
    public function addWordsFromString(string $words, string $separator = ' '): static
    {
        return $this->addWords(explode($separator, $words));
    }

    /**
     * Get the words that are stored against this object.
     *
     * @return array[]
     */
    public function getWords(): array
    {
        return $this->words;
    }

    /**
     * Get the actual words that have been added to the object's dictionary words within this session that might not
     * have been in prior.
     *
     * @return array
     */
    public function getWordsAdded(): array
    {
        return $this->wordsAdded;
    }

    /**
     * Get the words that are stored against this object, alphabetically associated.
     *
     * @return array
     */
    public function getWordsAlphabeticallyAssociated(): array
    {
        $return = [];

        foreach ($this->words as $word) {
            $return[$word[0]][] = $word;
        }

        return $return;
    }

    /**
     * This method is utilised for committing the words to memory; this ensures that the persistence of words that had
     * been added to the object's lifespan.
     *
     * @return void
     */
    public function commitWordsAddedToStorage(): void
    {
        foreach ($this->wordsAdded as $word) {
            // given that this particular word would not yet have been in the dictionary, we're going to add this
            // to the dictionary text files; so that when it comes to re-building the exported file types; we are going
            // to have this to do it from.
            $this->filesystem->writeLine($this->getPath("/text/{$word[0]}.txt"), $word);
        }
    }

    /**
     * @param string|null $path
     * @return $this
     */
    public function setPath(?string $path = null): static
    {
        $this->path = $path;

        return $this;
    }

    /**
     * return the file location
     *
     * @param string|null $path
     * @return string
     */
    public function getPath(?string $path = null): string
    {
        return $this->path . $path;
    }
}
