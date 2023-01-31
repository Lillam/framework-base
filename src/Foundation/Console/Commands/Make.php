<?php

namespace Vyui\Foundation\Console\Commands;

use Exception;
use Vyui\Foundation\Application;
use Vyui\Contracts\Filesystem\Filesystem;

class Make extends Command
{
	/**
	 * @var Application
	 */
	protected Application $application;

	/**
	 * @var Filesystem
	 */
	protected Filesystem $filesystem;

	/**
	 * @var string[]
	 */
	protected array $makes = [
		'request' => '/app/Http/Requests/',
		'model' => '/app/Models/',
		'command' => '/app/Console/',
		'controller' => '/app/Http/Controllers/',
		'service' => '/app/Services/'
	];

	/**
	 * The particular snippet that the system is currently set to be making.
	 *
	 * @var string
	 */
	protected string $making = '';

	/**
	 * The namespace of the file that will be generated.
	 *
	 * @var string
	 */
	protected string $namespace = '';

	/**
	 * The class of the file that will be generated.
	 *
	 * @var string
	 */
	protected string $class = '';

	/**
	 * @param Application $application
	 * @param Filesystem $filesystem
	 * @param array $arguments
	 * @throws Exception
	 */
	public function __construct(Application $application, Filesystem $filesystem, array $arguments = [])
	{
		$this->application = $application;
		$this->filesystem = $filesystem;

		parent::__construct([$making, $class] = $arguments);

		$this->setMaking($making)
		  	 ->setNamespace($class)
			 ->setClass($class);
	}

	/**
	 * @param string $make
	 * @return $this
	 * @throws Exception
	 */
	public function setMaking(string $make): static
	{
		if (! array_key_exists($make, $this->makes)) {
			throw new Exception(
				"[$make] does not exist within " . implode(',', array_flip($this->makes))
			);
		}

		$this->making = $make;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getMaking(): string
	{
		return $this->making;
	}

	/**
	 * @return string
	 */
	public function getMakingDirectory(): string
	{
		return $this->application->getBasePath( "{$this->makes[$this->making]}{$this->getNamespace()}");
	}

	/**
	 * @return string
	 */
	public function getMakingFile(): string
	{
		return $this->getMakingDirectory() . "/{$this->getClass('.php')}";
	}

	/**
	 * Set the namespace of the class that the command will be making.
	 *
	 * @param string $class
	 * @return $this
	 */
	public function setNamespace(string $class): static
	{
		$classPieces = explode('/', $class);
		array_pop($classPieces);

		$this->namespace = implode('/', $classPieces);

		return $this;
	}

	/**
	 * Get the namespace of the class that the command will be making.
	 *
	 * @param bool $asPath
	 * @return string
	 */
	public function getNamespace(bool $asPath = true): string
	{
		return $asPath
			? $this->namespace
			: '\\' . str_replace('/', '\\', $this->namespace);
	}

	/**
	 * set the class that the command will be making.
	 *
	 * @param string $class
	 * @return $this
	 */
	public function setClass(string $class): static
	{
		$classPieces = explode('/', $class);
		$this->class = end($classPieces);
		return $this;
	}

	/**
	 * Get the class that the command will be making.
	 *
	 * @param string|null $extension
	 * @return string
	 */
	public function getClass(?string $extension = null): string
	{
		return $this->class . $extension;
	}

	/**
	 * Acquire the stub file for the particular type of resource that the console is intending to make.
	 *
	 * @return string
	 */
	private function getStubFileContents(): string
	{
		return $this->filesystem->get(__DIR__ . "/stubs/{$this->getMaking()}.stub");
	}

	/**
	 * Compile the stub file.
	 *
	 * @return string
	 */
	private function compile(): string
	{
		return str_replace(['{{ class }}', '{{ namespace }}'], [
			$this->getClass(),
			$this->getNamespace(false)
		], $this->getStubFileContents());
	}

	/**
	 * Execute the command.
	 *
	 * @return int
	 */
	public function execute(): int
	{
		$this->filesystem->makeDirectory($this->getMakingDirectory(), 0755, true);
		$this->filesystem->put($this->getMakingFile(), $this->compile());

		$this->output->print(ucwords($this->getMaking()) . ' has been generated successfully');

		return 1;
	}
}