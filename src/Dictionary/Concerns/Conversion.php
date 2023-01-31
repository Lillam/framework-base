<?php

namespace Vyui\Dictionary\Concerns;

use Vyui\Support\Helpers\_String;

trait Conversion
{
	/**
     * Convert files that are stored within the designated path; and iterate over each item and append them into a
     * corresponding .php file to be used with ease later.
     *
	 * @return void
	 */
	public function convertDictionaryFilesToPHPFiles(): void
	{
        if (empty($this->words)) {
            $this->load();
        }

		foreach ($this->getWordsAlphabeticallyAssociated() as $alphabetPiece => $words) {
            $filename = "/array/$alphabetPiece.php";

            $string = _String::fromString('<?php return [');

			foreach ($words as $word) {
                $string->append("\n")
                       ->append("   '$word' => '',");
			}

            $string->append("\n")
                   ->append('];');

            // delete the current existing file such as "a.php" and remove it, we remove it to make it simpler to
            // the above data to the file that we're due to make.
			$this->filesystem->delete($this->getPath($filename));

            // take the string of which has been built within the loop of lines, after collating the text file we can
            // store it as a PHP Format and save to the storage.
			$this->filesystem->put($this->getPath($filename), $string->toString());
		}
	}
}