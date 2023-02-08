<?php

namespace Vyui\Services\View\ViewEngines\Blade\Compiles;

trait CompilesIf
{
    /**
     * @param string $template
     * @return string
     */
    private function compileIf(string $template): string
    {
        return preg_replace_callback_array([
            "#@if\(([^)]+)\)#" => function ($matches) {
                return "<?php if ($matches[1]): ?>";
            },
            "#@endif#" => function () {
                return '<?php endif; ?>';
            }
        ], $template);
    }
}