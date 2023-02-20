<?php

namespace Vyui\Services\View\ViewEngines\Vyui;

use Vyui\Services\View\View;
use Vyui\Contracts\View\Engine;
use Vyui\Support\Helpers\_String;
use Vyui\Foundation\Http\Response;
use Vyui\Services\View\HasViewManager;

class VyuiEngineV1 implements Engine
{
    use HasViewManager;

    /**
     * the current view that we're working with.
     *
     * @var View
     */
    protected View $view;

    /**
     * The current cached file that's in memory for this iteration of operation.
     *
     * @var string
     */
    protected string $currentCacheFile;

    /**
     * @var string[]
     */
    protected array $loadedTemplates = [];

    /**
     * @var array
     */
    protected array $layouts = [];

    /**
     * @var array
     */
    protected array $yields = [];

    /**
     * @var string[]
     */
    protected array $regex = [
        'yield'   => '/#\[yield: (.*)\]/',
        'extends' => '/#\[extends: (layouts\/master)\]/',
        'section' => '/(\#\[section: (.*)\])([\S\s]*?)(\#\[\/section\])/',
        'if'      => '/(\#\[if: (.*)\])([\S\s]*?)(\#\[\/if\])/',
        'for'     => '/(\#\[for: (.*)\])([\S\s]*?)(\#\[\/for\])/',
        'foreach' => '/(\#\[foreach: (.*)\])([\S\s]*?)(\#\[\/foreach\])/',
        'echo'    => '/#\[echo: (.*)\]/'
    ];

    /**
     * todo - right now this particular method is creating files for each individual component however I want to simplify
     *        the process at the point when a file gets created and stored into a file for later retrieval. I'm intending
     *        that at the point of a file is made, the file will have everything that's needed to be built.
     *
     * @param View $view
     * @return Response
     */
    public function render(View $view): Response
    {
        $this->view = $view;

        $this->currentCacheFile = $this->manager->getStoragePath(
            _String::fromString($view->getHashedTemplate())
                ->append('.php')
        );

        if (! file_exists($this->currentCacheFile) || filemtime($view->template) > filemtime($this->currentCacheFile)) {
            // todo put the line below back into here after debugging purposes; this is to stop the need for having to
            //      to make any alterations.
            //          the below code wants to be included back in here when done with the testing portion of things.
        }

        $this->getViewManager()
             ->putFileContent(
                 $this->currentCacheFile, $this->compile($this->getFileToCompile($view))
             );

        $content = $this->getCompiledFileContents($this->currentCacheFile, $view->getData());

        if ($layout = $this->getLayout($this->view->getHashedTemplate())) {
            return view($layout, $view->getData());
        }

        return new Response($content);
    }

    /**
     * @param View $view
     * @return string
     */
    private function getFileToCompile(View $view): string
    {
        return $this->getViewManager()->getFileContent($view->template);
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

        $this->loadedTemplates[] = $cachedFilePath;
        include $cachedFilePath;

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * @param string $template
     * @return string
     */
    private function compile(string $template): string
    {
        return preg_replace_callback_array([
            $this->regex['yield']   => fn ($matches) => $this->compileYield($matches),
            $this->regex['extends'] => fn ($matches) => $this->compileExtends($matches),
            $this->regex['section'] => fn ($matches) => $this->compileSection($matches),
            $this->regex['if']      => fn ($matches) => $this->compileIf($matches),
            $this->regex['for']     => fn ($matches) => $this->compileFor($matches),
            $this->regex['foreach'] => fn ($matches) => $this->compileForEach($matches),
            $this->regex['echo']    => fn ($matches) => $this->compileEcho($matches)
        ], $template);
    }

    /**
     * @param array $matches
     * @return string
     */
    protected function compileYield(array $matches): string
    {
        return "<?php echo \$this->getYield('$matches[1]'); ?>";
    }

    /**
     * @param array $matches
     * @return string
     */
    protected function compileExtends(array $matches): string
    {
        $this->layouts[$this->view->getHashedTemplate()] = $matches[1];

        return "<?php \$this->extends('$matches[1]'); ?>";
    }

    /**
     * @param array $matches
     * @return string
     */
    protected function compileSection(array $matches): string
    {
        $this->yields[$matches[2]] = $content = $matches[3];

        return "<?php \$this->startSection('$matches[2]'); ?>" .
               $content .
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

    /**
     * @param string $yielding
     * @return string
     */
    public function getYield(string $yielding): string
    {
        $content = $this->yields[$yielding] ?? '';
        unset($this->yields[$yielding]);
        return $content;
    }

    public function startSection()
    {

    }

    public function endSection()
    {

    }

    /**
     * Extending a template.
     *
     * @param string $template
     * @return $this
     */
    public function extends(string $template): static
    {
        $this->layouts[$this->view->getHashedTemplate()] = $template;

        return $this;
    }

    /**
     * @param string $cachedFile
     * @return string|null
     */
    protected function getLayout(string $cachedFile): ?string
    {
        return $this->layouts[$cachedFile] ?? null;
    }

    /**
     * A shit-show of a method at the moment that just simplifies the process of carrying the data when returning another
     * view and passing the necessary pieces in where the view will then be able to begin rendering the information that
     * has been passed.
     *
     * @param $content
     * @return void
     */
    private function carryData($content): void
    {
        foreach ($this->yields as $yieldKey => $yield) {
            $this->yields[$yieldKey] = $content;
        }
    }
}