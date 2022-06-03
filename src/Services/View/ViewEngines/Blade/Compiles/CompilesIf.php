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
        $template = preg_replace_callback('#@if\(([^)]+)\)#', function ($matches) {
            return "<?php if ($matches[1]): ?>";
        }, $template);

        return preg_replace_callback('#@endif#', function () {
            return '<?php endif; ?>';
        }, $template);
    }
}