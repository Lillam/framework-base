<?php

namespace Vyui\Services\Translation;

use Vyui\Services\Service;
use Vyui\Contracts\Translation\Translator as TranslatorContract;

class TranslatorService extends Service
{
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
			(new Translator())->setFileLocation($this->application->getBasePath($this->path))
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