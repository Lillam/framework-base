<?php

namespace Vyui\Services\View\ViewEngines\Vyui;

use Vyui\Services\View\View;
use Vyui\Contracts\View\Engine;
use Vyui\Foundation\Http\Response;
use Vyui\Services\View\HasViewManager;

class VyuiEngine implements Engine
{
    use HasViewManager;

    /**
     * @var array
     */
    protected array $layouts = [];

    /**
     * @param View $view
     * @return Response
     */
    public function render(View $view): Response
    {
        extract($view->data);
        ob_start();
        include $view->template;
        $content = ob_get_contents();
        ob_end_clean();

        if ($layout = $this->layouts[$view->template] ?? null) {
            return view($layout, array_merge(
                $view->data,
                ['content' => $content]
            ));
        }

        return new Response($content);
    }

    /**
     * Extending a template.
     *
     * @param string $template
     * @return $this
     */
    public function extends(string $template): static
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        $this->layouts[realpath($backtrace[0]['file'])] = $template;
        return $this;
    }

    /**
     * @param string $content
     * @return string
     */
    public function escape(string $content): string
    {
        return htmlspecialchars($content, ENT_QUOTES);
    }
}