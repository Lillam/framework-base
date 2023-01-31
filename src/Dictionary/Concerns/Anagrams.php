<?php

namespace Vyui\Dictionary\Concerns;

trait Anagrams
{
	/**
	 * The current in memory anagram.
	 *
	 * @var string
	 */
	protected string $anagram;

	/**
	 * The current min word length of words within anagram.
	 *
	 * @var int
	 */
	protected int $anagramMin = 2;

	/**
	 * The current max word length of words within anagram.
	 *
	 * @var int
	 */
	protected int $anagramMax = 7;

	/**
	 * Words that can be found from an anagram.
	 *
	 * @var array
	 */
	protected array $wordsFromAnagram = [];

	/**
	 * Get the current anagram stored in memory.
	 *
	 * @return string
	 */
	public function getAnagram(): string
	{
		return $this->anagram;
	}

	/**
	 * Set the current anagram into memory.
	 *
	 * @param string $anagram
	 * @return static
	 */
	public function setAnagram(string $anagram): static
	{
		$this->anagram = $anagram;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getAnagramMin(): int
	{
		return $this->anagramMin;
	}

	/**
	 * @param int $min
	 * @return $this
	 */
	public function setAnagramMin(int $min): static
	{
		$this->anagramMin = $min;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getAnagramMax(): int
	{
		return $this->anagramMax;
	}

	/**
	 * @param int $max
	 * @return $this
	 */
	public function setAnagramMax(int $max): static
	{
		$this->anagramMax = $max;

		return $this;
	}

	/**
	 * Is the word valid for the anagram in question.
	 *
	 * @param string $word
	 * @return bool
	 */
	public function isValidAnagram(string $word): bool
	{
		return $word !== '' &&
			   strlen($word) >= $this->getAnagramMin() &&
			   strlen($word) <= $this->getAnagramMax();
	}

	/**
	 * return the opposite, if there are no letters left in the word after removing the anagram words
	 * we will return true, otherwise this will return the opposite of true if there happens to be words left.
	 *
	 * @param string $word
	 * @return bool
	 */
	public function isValidAnagramToWord(string $word): bool
	{
		foreach (str_split($this->getAnagram()) as $letter) {
			$word = preg_replace("/$letter/", '', $word, 1);
		}

		return ! $word;
	}

	/**
	 * Add a word to the collection of anagrams.
	 *
	 * @param string $word
	 * @return void
	 */
	public function addWordToAnagrams(string $word): void
	{
		$this->wordsFromAnagram[] = $word;
	}

	/**
	 * Acquire all the words that can be found from an anagram.
	 *
	 * @return array
	 */
	public function findWordsFromAnagram(): array
	{
		foreach ($this->filesystem->files($this->getPath("/text")) as $file) {
			array_map(function (string $line) {
                if ($this->isValidAnagram($line) && $this->isValidAnagramToWord($line)) {
                    $this->addWordToAnagrams($line);
                }
                $this->wordsInDictionary += 1;
            }, $this->filesystem->lines($file));
		}

		return $this->wordsFromAnagram;
	}

	/**
	 * Acquire all the words that can be found from an anagram but in reverse.
	 *
	 * @return array
	 */
	public function findWordsFromAnagramInReverse(): array
	{
		return $this->wordsFromAnagram = array_reverse($this->findWordsFromAnagram());
	}
}