<?php

namespace Vyui\Services\View\ViewEngines\Basic;

use Vyui\Services\View\View;
use Vyui\Foundation\Http\Response;
use Vyui\Services\View\ViewEngines\Engine as BaseEngine;

class Engine extends BaseEngine
{
    /**
     * Render the template.
     *
     * @param View $view
     * @return Response
     */
    public function render(View $view): Response
    {
        extract($view->getData());
        ob_start();

        include $view->getTemplate();

        $contents = ob_get_contents();
        ob_get_clean();

        return new Response($contents ?? '');
    }
}
