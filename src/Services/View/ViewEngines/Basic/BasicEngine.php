<?php

namespace Vyui\Services\View\ViewEngines\Basic;

use Vyui\Services\View\View;
use Vyui\Contracts\View\Engine;
use Vyui\Foundation\Http\Response;
use Vyui\Services\View\HasViewManager;

class BasicEngine implements Engine
{
    use HasViewManager;

    /**
     * Render the template.
     *
     * @param View $view
     * @return Response
     */
    public function render(View $view): Response
    {
//        $contents = file_get_contents($view->template);
//
//        foreach ($view->data as $key => $datum) {
//            $contents = str_replace('{' . $key . '}', $datum, $contents);
//        }

        extract($view->getData());
        ob_start();

        include $view->getTemplate();

        $contents = ob_get_contents();
        ob_get_clean();

        return new Response($contents ?? '');
    }
}