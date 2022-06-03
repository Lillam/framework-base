<?php

namespace Vyui\Services\View\ViewEngines\Blade\Compiles;

trait CompilesEchos
{
    /**
     * @param string $template
     * @return string
     */
    public function compileEcho(string $template): string
    {
        return preg_replace_callback('#{{(.*)}}#', function ($matches) {
            return '<?= $this->escape(' . $matches[1] . '); ?>';
        }, $template);
    }

    /**
     * @param string $template
     * @return string
     */
    private function compileUnsafeEcho(string $template): string
    {
        return preg_replace_callback('#{!!(.*)!!}#', function ($matches) {
            return "<?= $matches[1]; ?>";
        }, $template);
    }
}