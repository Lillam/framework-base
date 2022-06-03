<?php

namespace Vyui\Contracts\View;

use Vyui\Services\View\View;
use Vyui\Foundation\Http\Response;

interface Engine
{
    /**
     * Render the template.
     *
     * @param View $view
     * @return Response
     */
    public function render(View $view): Response;
}