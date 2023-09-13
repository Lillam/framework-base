<?php

namespace Vyui\Services\View\ViewEngines\Blade;

use Vyui\Services\View\View;
use Vyui\Contracts\View\Engine;
use Vyui\Foundation\Http\Response;
use Vyui\Services\View\HasViewManager;

class BladeEngine implements Engine
{
    use HasViewManager;

    /**
     * @var array
     */
    protected array $layouts = [];

    /**
     * @var array
     */
    protected array $yields = [];

    /**
     * @param View $view
     * @return Response
     */
    public function render(View $view): Response
    {
        $hash = md5($view->template);
        $cachedFile = $this->manager->getStoragePath("$hash.php");

        if (! file_exists($hash) || filemtime($view->template) > filemtime($cachedFile)) {
            file_put_contents($cachedFile, $this->compile(file_get_contents($view->template)));
        }

        // extract the data that will have been passed to the view... so that we're going to be capable of referencing
        // these variables within the view itself.
        extract($view->data);
        ob_start();

        // we are going to instead, be including a cached file, rather than the main file, because there's no real need
        // to keep compiling something that might have already been compiled. This will mean a lack of need to utilise
        // resources on a view by view basis.
        include $cachedFile;

        $content = ob_get_contents();
        ob_end_clean();

        // if we have an extension going on, then we're going to want to acquire the necessary information from that file
        // and recursively map all the way down, until the last template had been rendered and cached.
        if ($layout = $this->layouts[$cachedFile] ?? null) {
            return view($layout, array_merge(
                $view->data,
                ['content' => $content]
            ));
        }

        // return the content if there was no need for an extension... and we can just end it right here.
        return new Response($content);
    }

    /**
     * Compile the template content.
     *
     * @param string $template
     * @return string
     */
    public function compile(string $template): string
    {
        $template = $this->compileYield($template);
        $template = $this->compileExtends($template);
        $template = $this->compileSection($template);
        $template = $this->compileIf($template);
        $template = $this->compileForEachLoop($template);
        $template = $this->compileForLoop($template);
        $template = $this->compileEcho($template);
        $template = $this->compileUnsafeEcho($template);

        return $template;
    }

    /**
     * @param string $template
     * @return string
     */
    private function compileEcho(string $template): string
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
     * @param string $template
     * @return string
     */
    private function compileSection(string $template): string
    {
        return preg_replace_callback('#@section\(([^)]+)\)#', function ($matches) use (&$template) {
            // capture the yield piece so that we can store within the engine that the yield component is going to be
            // referenced by what's been captured.
            $yielding = str_replace(["'", '"'], '', $matches[1]);

            // upon capturing the yield, we are going to look for this particular yield piece within the template and
            // if we can find it, then we're going to compile the yield segment into the content section which will
            // later be referenced.
            $template = preg_replace_callback_array(
                [
                    "/(?<=@section\(\"$yielding\"\)\n)([\S\s]*?)(?=@endsection)/" => function ($matches) use ($yielding) {
                        $this->yield($yielding, $this->compile($matches[0]));
                        return '';
                    },
                    "/@section\(\"$yielding\"\)/" => fn () => '',
                    "/@endsection/" => fn () => ''
                ],
                $template
            );
            return $template;
        }, $template);
    }

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

    /**
     * @param string $template
     * @return string
     */
    private function compileForLoop(string $template): string
    {
        $template = preg_replace_callback('#@for\(([^)]+)\)#', function ($matches) {
            return "<?php for ($matches[1]): ?>";
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

        return preg_replace_callback('#@endforeach#', function () {
            return "<?php endforeach; ?>";
        }, $template);
    }

    /**
     * @param string $template
     * @return string
     */
    private function compileYield(string $template): string
    {
        return preg_replace_callback('#@yield\(([^)]+)\)#', function ($matches) {
            return '<?= $this->getYield("' . str_replace("'", '', $matches[1]) .  '"); ?>';
        }, $template);
    }

    /**
     * Add a section block (yield) to append all the information to acquire back at a later point.
     *
     * @param string $yield
     * @param string $yieldContent
     * @return void
     */
    private function yield(string $yield, string $yieldContent = ''): void
    {
        $this->yields[$yield] = $this->compile($yieldContent);
    }

    /**
     * @param string $yielding
     * @return string
     */
    public function getYield(string $yielding): string
    {
        return $this->yields[$yielding] ?? '';
    }

    /**
     * @param string $section
     * @return string
     */
//    public function section(string $section): string
//    {
//        if (! isset($this->yields[$section])) {
//            return '';
//        }
//
//        $content = $this->compile($this->yields[$section]);
//        unset($this->yields[$section]);
//        return $content;
//    }

    /**
     * Extending a template.
     *
     * @param string $template
     * @return $this
     */
    public function extends(string $template): static
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        $this->layouts[realpath($backtrace[0]['file'])] = $this->compile($template);
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