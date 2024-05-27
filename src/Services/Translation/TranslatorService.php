<?php

namespace Vyui\Services\Translation;

use Vyui\Services\Service;

class TranslatorService extends Service
{
    /**
     * The languages that are available for the application.
     *
     * @var string[]
     */
    static array $languages = [];

    /**
     * The currencies that are available for the application.
     *
     * @var string[]
     */
    static array $currencies = [];

    /**
     * the location in which the path for translations will be located.
     *
     * @var string
     */
    protected string $path = '/resources/languages';

    /**
     * Register the translator service into the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->application->instance(
            TranslatorContract::class,
            (new Translator)->setFileLocation($this->application->getBasePath($this->path))
                            ->setLanguage(config('app.language.default'))
        );
    }

    /**
     * Bootstrap the provider.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        $this->bootstrapped = true;
    }
}
