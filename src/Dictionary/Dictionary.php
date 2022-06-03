<?php

namespace Vyui\Dictionary;

use Vyui\Contracts\Filesystem\Filesystem;

use Vyui\Dictionary\Concerns\Anagrams;
use Vyui\Dictionary\Concerns\Conversion;

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
     * @param string|null $path
     * @return $this
     */
    public function setPath(?string $path = null): self
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