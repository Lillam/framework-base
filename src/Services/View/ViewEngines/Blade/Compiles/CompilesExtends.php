<?php

namespace Vyui\Services\View\ViewEngines\Blade\Compiles;

trait CompilesExtends
{
    /**
     * @var array
     */
    protected array $layouts = [];

    /**
     * @param string $template
     * @return string
     */
    private function compileExtends(string $template): string
    {
        return preg_replace_callback('#@extends\(([^)]+)\)#', function ($matches) {
            return '<?php $this->extends(' . $matches[1] . '); ?>';
        }, $template);
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
}
