<?php

namespace Vyui\Services\View;

use Exception;
use Vyui\Contracts\View\Engine;
use Vyui\Foundation\Http\Response;
use Vyui\Contracts\Filesystem\Filesystem;

class ViewManager
{
    /**
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @var string[]
     */
    protected array $paths = [];

    /**
     * @var Engine[]
     */
    protected array $engines = [];

    /**
     * The path of which all views will be cached to.
     *
     * @var string|null
     */
    protected ?string $storagePath = null;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Add a path to the ViewManager, this is where the view manager will know where to look for views in the system.
     *
     * @param string $path
     * @return $this
     */
    public function registerPath(string $path): static
    {
        $this->paths[] = $path;

        return $this;
    }

    /**
     * Register where the files will be stored to upon being compiled. (utilised for optimising the unnecessary
     * re-compilation of files that have already been compiled).
     *
     * @param string $path
     * @param bool $makeDirectory
     * @return $this
     */
    public function registerStoragePath(string $path, bool $makeDirectory = false): static
    {
        // if we have told the system it should attempt to make the directory upon being instantiated, then we're going
        // to make the directory, providing that the directory hasn't already been made yet.
        if ($makeDirectory && ! is_dir($path)) {
            mkdir($path);
        }

        $this->storagePath = $path;

        return $this;
    }

    /**
     * Get the storage path that has been set for the view manager.
     *
     * @param string $path
     * @return string
     */
    public function getStoragePath(string $path = ''): string
    {
        return $this->storagePath . $path;
    }

    /**
     * Add an engine against each extension.
     *
     * @param string $extension
     * @param Engine $engine
     * @return $this
     */
    public function registerEngine(string $extension, Engine $engine): static
    {
        $this->engines[$extension] = $engine->setViewManager($this);

        return $this;
    }

    /**
     * Render the template.
     *
     * @param string $template
     * @param array $data
     * @return Response
     * @throws Exception
     */
    public function resolve(string $template, array $data = []): Response
    {
        foreach ($this->engines as $extension => $engine) {
            foreach ($this->paths as $path) {
                if (is_file($file = "$path$template.$extension")) {
                    return new Response((string) new View($engine, realpath($file), $data));
                }
            }
        }

        // if we haven't managed to find a necessary engine to handle the particular template, we're going to render
        // an error instead, letting the developer know that there is no template engine dedicated for that particular
        // file.
        throw new Exception("Target [$template] cannot be templated via any Engine registered");
    }

    /**
     * Affirm that the template exists as a registered template and can be handled.
     *
     * @param string $template
     * @return bool
     */
    public function templateDoesExist(string $template): bool
    {
        return isset($this->engines[$template]);
    }

    /**
     * Affirm that the template doesn't exist as a registered template that can be handled.
     *
     * @param string $template
     * @return bool
     */
    public function templateDoesNotExist(string $template): bool
    {
        return ! $this->templateDoesExist($template);
    }

    /**
     * @param string|null $file
     * @return string
     */
    public function getResourcePath(?string $file): string
    {
        return $this->paths[0] . $file;
    }

    /**
     * @param string $file
     * @return string
     */
    public function getFileContent(string $file): string
    {
        return $this->filesystem->get($file);
    }

    /**
     * @param string $file
     * @param string $contents
     * @return void
     */
    public function putFileContent(string $file, string $contents): void
    {
        $this->filesystem->put($file, $contents);
    }
}