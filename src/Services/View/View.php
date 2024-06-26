<?php

namespace Vyui\Services\View;

use Vyui\Services\View\ViewEngines\EngineContract as Engine;

class View
{
    /**
     * The engine of which is going to be taking care of this particular view.
     *
     * @var Engine
     */
    protected Engine $engine;

    /**
     * The file of which we're going to be rendering.
     *
     * @var string
     */
    public string $template;

    /**
     * The file of which will have been stored.
     *
     * @var string
     */
    public string $templateHash;

    /**
     * The data that wants to be passed to the view.
     *
     * @var array
     */
    public array $data = [];

    /**
     * @param Engine $engine
     * @param string $template
     * @param array $data
     */
    public function __construct(Engine $engine, string $template, array $data = [])
    {
        $this->engine = $engine;
        $this->template = $template;
        $this->data = $data;
    }

    /**
     * Method for acquiring the template against the view.
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * Get the template string as a hashed version, this should never change unless the name of the file changes.
     *
     * @return string
     */
    public function getHashedTemplate(): string
    {
        if (! empty($this->templateHash)) {
            return $this->templateHash;
        }

        return $this->templateHash = md5($this->template);
    }

    /**
     * Method for acquiring the data that's against the view.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Turn the view class instance into a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->engine->render($this);
    }
}
