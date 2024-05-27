<?php

namespace Vyui\Services\Translation;

class Translator implements TranslatorContract
{
    /**
     * Where the files are located for all translations.
     *
     * @var string
     */
    protected string $fileLocation;

    /**
     * The language that's set against the translator and what language we'd want to be pulling
     * from the language files.
     *
     * @var string
     */
    protected string $language = 'EN';

    /**
     * Set the file location where all translation files will be found.
     *
     * @param string $fileLocation
     * @return $this
     */
    public function setFileLocation(string $fileLocation): self
    {
        $this->fileLocation = $fileLocation;

        return $this;
    }

    /**
     * Set the language of the translator.
     *
     * @param string $language
     * @return $this
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Take in a key and begin looking amongst the translation files for the right word to utilise.
     *
     * @param string $key
     * @return string
     */
    public function translate(string $key): string
    {
        return '';
    }
}
