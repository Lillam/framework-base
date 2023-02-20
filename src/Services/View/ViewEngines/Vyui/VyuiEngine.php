<?php

namespace Vyui\Services\View\ViewEngines\Vyui;

use Vyui\Services\View\View;
use Vyui\Contracts\View\Engine;
use Vyui\Support\Helpers\_String;
use Vyui\Foundation\Http\Response;
use Vyui\Services\View\HasViewManager;

class VyuiEngine implements Engine
{
    use HasViewManager;

    /**
     * Layout blocks that will be "extended"
     *
     * @var array
     */
    protected array $layouts = [];

    /**
     * Little blocks of content that will be rendered back to the frontend.
     *
     * @var array
     */
    protected array $yields = [];

    /**
     * A set of regex rules that will aid the compiling process.
     *
     * @var string[]
     */
    protected array $compilerRegex = [
        'yield'   => '/#\[yield: (.*)\]/',
        'extends' => '/#\[extends: (layouts\/master)\]/',
        'section' => '/(\#\[section: (.*)\])([\S\s]*?)(\#\[\/section\])/',
        'if'      => '/(\#\[if: (.*)\])([\S\s]*?)(\#\[\/if\])/',
        'for'     => '/(\#\[for: (.*)\])([\S\s]*?)(\#\[\/for\])/',
        'foreach' => '/(\#\[foreach: (.*)\])([\S\s]*?)(\#\[\/foreach\])/',
        'echo'    => '/#\[echo: (.*)\]/'
    ];

    /**
     * Take a view, and look to see if we have a view in storage for this compilation otherwise compile it then load it
     * to display to the user.
     *
     * @issue -> Currently if extending a file, the file of which was extended (if it changes) won't trigger a compile
     *           again meaning that you'd then need to clear the view cache which is non desirable so this will need
     *           to change somewhere; either that or have a compiled file name based on the view.
     *
     * @param View $view
     * @return Response
     */
    public function render(View $view): Response
    {
        $cache = $this->manager->getStoragePath(
            _String::fromString($view->getHashedTemplate())->append('.php')
        );

        if ($this->shouldRecompile($cache, $view)) {
            // Acquire the original content from the view that has been passed in.
            $originalContent = $this->getViewManager()->getFileContent($view->template);
            // Pre-compile the original content which will look for key-words, like yield, extends and begin stripping
            // and making sections and blocks within the engine, so that they can be mapped accordingly to its' main
            // template.
            $precompiled = $this->preCompile($originalContent);
            // compile the content finally, which will begin attaching all the above yields, sections and layouts to
            // the final template and then dump them into a cached PHP file.
            $compiled = $this->compile($precompiled);
            // place the contents that has been compiled into storage so that we can simply acquire the file from the
            // build and then execute the PHP.
            $this->getViewManager()->putFileContent($cache, $compiled);
        }

        return new Response($this->getCompiledFileContents($cache, $view->getData()));
    }

    /**
     * Look for a few keywords, such as "extends" and we are going to replace this with even more content.
     *
     * @param string $content
     * @return string
     */
    public function preCompile(string $content): string
    {
        $content = preg_replace_callback_array([
            $this->compilerRegex['section'] => function ($matches) {
                $this->yields[$matches[2]] = $matches[0];
            }
        ], $content);

        $content = preg_replace_callback_array([
            $this->compilerRegex['extends'] => function ($matches) {
                // here we are going to look for a file that has been matched within the extends...
                return $this->layouts[$matches[1]] = $this->getViewManager()->getFileContent(
                    $this->getViewManager()->getResourcePath("$matches[1].vyui.php")
                );
            }
        ], $content);

        return preg_replace_callback_array([
            $this->compilerRegex['yield'] => function ($matches) {
                return $this->yields[$matches[1]] ?? '';
            }
        ], $content);
    }

    /**
     * @param string $content
     * @return string
     */
    public function compile(string $content): string
    {
        return preg_replace_callback_array([
            $this->compilerRegex['section'] => fn ($matches) => $this->compileSection($matches),
            $this->compilerRegex['if']      => fn ($matches) => $this->compileIf($matches),
            $this->compilerRegex['for']     => fn ($matches) => $this->compileFor($matches),
            $this->compilerRegex['foreach'] => fn ($matches) => $this->compileForEach($matches),
            $this->compilerRegex['echo']    => fn ($matches) => $this->compileEcho($matches)
        ], $content);
    }

    private function compileSection(array $matches): string
    {
        return "<?php \$this->startSection('$matches[2]'); ?>" .
               $matches[3] .
               "<?php \$this->endSection('$matches[2]'); ?>";
    }

    /**
     * @param array $matches
     * @return string
     */
    protected function compileIf(array $matches): string
    {
        return "<?php if ($matches[2]): ?>" .
               $matches[3] .
               "<?php endif; ?>";
    }

    /**
     * @param array $matches
     * @return string
     */
    protected function compileFor(array $matches): string
    {
        return "<?php for ($matches[2]): ?>" .
               $matches[3] .
               "<?php endfor; ?>";
    }

    /**
     * @param array $matches
     * @return string
     */
    protected function compileForEach(array $matches): string
    {
        return "<?php foreach ($matches[2]): ?>" .
               $matches[3] .
               "<?php endforeach; ?>";
    }

    protected function compileEcho(array $matches): string
    {
        return "<?php echo $matches[1]; ?>";
    }

    public function startSection(): void
    {

    }

    public function endSection(): void
    {

    }

    /**
     * @param string $cachedFilePath
     * @param array $data
     * @return string
     */
    private function getCompiledFileContents(string $cachedFilePath, array $data): string
    {
        extract($data);
        ob_start();

        include $cachedFilePath;

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    private function shouldRecompile(string $cache, View $view): bool
    {
        return ! file_exists($cache) || filemtime($view->template) > filemtime($cache);
    }
}