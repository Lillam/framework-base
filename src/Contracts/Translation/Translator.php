<?php

namespace Vyui\Contracts\Translation;

use Vyui\Services\Translation\Translator as Translate;

interface Translator
{
    /**
     * Set the file location where all translation files will be found.
     *
     * @param string $fileLocation
     * @return Translate
     */
    public function setFileLocation(string $fileLocation): Translate;
}