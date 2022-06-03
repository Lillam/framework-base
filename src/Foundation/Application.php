<?php

namespace Vyui\Foundation;

use Vyui\Services\Service;
use Vyui\Foundation\Container\Container;
use Vyui\Contracts\Application as ApplicationContract;

class Application extends Container implements ApplicationContract
{
    /**
     * The base path for the application's installation.
     *
     * @var string
     */
    protected string $basePath = '';

    /**
     * The services of the application.
     *
     * @var array<string, Service>
     */
    protected array $services = [];

    /**
     * The registered services of the application.
     *
     * @var array<string, bool>
     */
    protected array $servicesRegistered = [];

    /**
     * @param string $basePath
     */
    public function __construct(string $basePath = '')
    {
        $this->setBasePath($basePath);
        $this->registerBaseBindings();
        $this->registerBaseServices();
    }

    /**
     * Register the base bindings that the application's container will need by default.
     *
     * @return void
     */
    public function registerBaseBindings(): void
    {
        static::setInstance($this);

        $this->instance(self::class, $this);
    }

    /**
     * Register all the base providers that application's container will need by default.
     *
     * @return void
     */
    public function registerBaseServices(): void
    {
        foreach ([
            \Vyui\Services\Environment\EnvironmentService::class,
            \Vyui\Services\Config\ConfigService::class,
            \Vyui\Services\Filesystem\FilesystemService::class,
            \Vyui\Services\Translation\TranslatorService::class,
            \Vyui\Services\Facades\FacadeService::class,
            \Vyui\Services\Exceptions\ExceptionService::class,
            \Vyui\Services\Routing\RoutingService::class,
            \Vyui\Services\View\ViewService::class,
		 	\Vyui\Services\Database\DatabaseService::class
        ] as $provider) {
			$this->register(new $provider($this), $provider);
		}
    }

    /**
     * Register a provider into the application.
     *
     * @param Service $provider
     * @param string|null $registerAs
     * @return void
     */
    public function register(Service $provider, string $registerAs = null): void
    {
        $provider->register();

        if ($registerAs === null) {
            $registerAs = (string) $provider;
        }

        $this->services[$registerAs] = $provider;
        $this->servicesRegistered[$registerAs] = true;
    }

    /**
     * Set the base path for the application.
     *
     * @param string $basePath
     * @return $this
     */
    public function setBasePath(string $basePath): self
    {
        $this->basePath = $basePath;

        return $this;
    }

    /**
     * Get the base path for the application.
     *
     * @param string|null $path
     * @return string
     */
    public function getBasePath(?string $path = null): string
    {
        return $this->basePath . $path;
    }

    /**
     * @return float
     */
    public function buildTime(): float
    {
        return microtime(true) - FRAMEWORK_START;
    }

	/**
	 * @param float $buildTime
	 * @return bool
	 */
	public function buildTimeFasterThan(float $buildTime): bool
	{
		return $this->buildTime() < $buildTime;
	}

    /**
     * Give control to the container in order to make the abstraction.
     *
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     */
    public function make(string $abstract, array $parameters = []): mixed
    {
        return parent::make($abstract, $parameters);
    }
}