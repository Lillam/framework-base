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
        'if'      => '/(\#\[if: (.*)\])([\S\s]*?)(\#\[\/if\])/'
    ];

    /**
     * @param View $view
     * @return Response
     */
    public function render(View $view): Response
    {
        $hash = md5($view->template);
        $cachedFile = $this->manager->getStoragePath("$hash.php");

        if (! file_exists($cachedFile) || filemtime($view->template) > filemtime($cachedFile)) {
            file_put_contents($cachedFile, $this->compile(file_get_contents($view->template)));
        }

        extract($view->data);
        ob_start();

        include $cachedFile;

        $content = ob_get_contents();
        ob_end_clean();

        if ($layout = $this->layouts[$cachedFile] ?? null) {
            $this->yields['body'] = $content;
            return view($layout, array_merge(
                $view->data,
                ['content' => $content, 'extended' => true]
            ));
        }

        // todo what we need to do is initially get the first loaded file and decide then what we're going to be doing
        //      with it, upon recursively collecting a bunch of files together, we can then pass it what the overall
        //      collection of files will be needing

        return new Response($content);
    }

    private function compile(string $template): string
    {
        return preg_replace_callback_array([
            $this->regex['yield']   => fn ($matches) => $this->compileYield($matches),
            $this->regex['extends'] => fn ($matches) => $this->compileExtends($matches),
            $this->regex['section'] => fn ($matches) => $this->compileSection($matches),
            $this->regex['if']      => fn ($matches) => $this->compileIf($matches)
        ], $template);
    }

    protected function compileYield(array $matches): string
    {
        return '<?= $this->getYield("' . $matches[1] .  '"); ?>';
    }

    protected function compileExtends(array $matches): string
    {
        return '<?php $this->extends("' . $matches[1] . '"); ?>';
    }

    protected function compileSection(array $matches): string
    {
        $content = $this->compile($matches[3]);
        $this->yields[$matches[2]] = $content;
        return <<<END
           <?php \$this->startSection("$matches[2]"); ?>
                $content
           <?php \$this->endSection("$matches[2]"); ?> 
        END;
    }

    protected function compileIf(array $matches): string
    {
        return "<?php if ($matches[2]): ?>" .
               $matches[3] .
               "<?php endif; ?>";
    }

    /**
     * @param string $yielding
     * @return string
     */
    public function getYield(string $yielding): string
    {
        return $this->yields[$yielding] ?? '';
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
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        $this->layouts[realpath($backtrace[0]['file'])] = $template;
        return $this;
    }
//
//    /**
//     * @param string $content
//     * @return string
//     */
//    public function escape(string $content): string
//    {
//        return htmlspecialchars($content, ENT_QUOTES);
//    }
}