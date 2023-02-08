<?php

namespace Vyui\Services\View\ViewEngines\Blade\Compiles;

trait CompilesLoops
{
    /**
     * @param string $template
     * @return string
     */
    private function compileForLoop(string $template): string
    {
        $template = preg_replace_callback('#@for\(([^)]+)\)#', function ($matches) {
            return "<?php for($matches[1]): ?>";
        }, $template);

        return preg_replace_callback('#@endfor#', function () {
            return "<?php endfor; ?>";
        }, $template);
    }

    /**
     * @param string $template
     * @return string
     */
    private function compileForEachLoop(string $template): string
    {
        $template = preg_replace_callback('#@foreach\(([^)]+)\)#', function ($matches) {
            return "<?php foreach($matches[1]): ?>";
        }, $template);

        return preg_replace_callback('#@endforeach#', function ($matches) {
            return "<?php endforeach; ?>";
        }, $template);
    }
}