<?php

namespace Vyui\Services\View\ViewEngines;

use Vyui\Services\View\View;
use Vyui\Foundation\Http\Response;
use Vyui\Services\View\ViewManager;

interface EngineContract
{
    /**
     * Render the template.
     *
     * @param View $view
     * @return Response
     */
    public function render(View $view): Response;

    /**
     * Set the View Manager that belongs to an Engine.
     *
     * @param ViewManager $manager
     * @return ViewManager
     */
    public function setViewManager(ViewManager $manager): static;
}
