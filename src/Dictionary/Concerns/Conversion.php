<?php

namespace Vyui\Dictionary\Concerns;

trait Conversion
{
	/**
	 * @return void
	 */
	public function convertDictionaryFilesToPHPFiles(): void
	{
		foreach ($this->filesystem->files($this->getPath()) as $file) {
			$string = '<?php return [';
			foreach ($this->filesystem->lines($file) as $key => $line) {
				$string .= "\n";
				$string .= "	$key => [";
				$string .= "\n";
				$string .= "		'word' => '$line',";
				$string .= "\n";
				$string .= "		'definition' => '',";
				$string .= "\n";
				$string .= "	],";
			}
			$string .= "\n";
			$string .= '];';

			$this->filesystem->delete(
				$this->getPath('/' . str_replace('.txt', '.php', $file->getFilename()))
			);

			$this->filesystem->put(
				$this->getPath('/' . str_replace('.txt', '.php', $file->getFilename())),
				$string
			);
		}
	}
}