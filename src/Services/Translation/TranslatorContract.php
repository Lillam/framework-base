<?php

namespace Vyui\Services\Translation;

interface TranslatorContract
{
    /**
     * Set the file location where all translation files will be found.
     *
     * @param string $fileLocation
     * @return Translate
     */
    public function setFileLocation(string $fileLocation): Translator;
}
