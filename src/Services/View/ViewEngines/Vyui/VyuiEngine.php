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
     * The current cache of the view we're trying to render. This will be utilised for storing the name of the file and
     * the time in which the file had been built against the time that exists against the real file.
     *
     * @var string
     */
    protected string $cache = '';

    /**
     * @var array
     */
    protected array $cacheComponents = [];

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
        'yield'   => '/( *)#\[yield: (.*)\]/',
        'extends' => '/#\[extends: (.*)\]/',
        'include' => '/#\[include: (.*)\]/',
        'section' => '/(\#\[section: (.*)\])([\S\s]*?)(\#\[\/section\])/',
        'if'      => '/(\#\[if: (.*)\])([\S\s]*?)(\#\[\/if\])/',
        'for'     => '/(\#\[for: (.*)\])([\S\s]*?)(\#\[\/for\])/',
        'foreach' => '/(\#\[foreach: (.*)\])([\S\s]*?)(\#\[\/foreach\])/',
        'echo'    => '/#\[echo: (.*)\]/',
    ];

    /**
     * Take a view, and look to see if we have a view in storage for this compilation otherwise compile it then load it
     * to display to the user.
     *
     * @issue -> Currently if extending a file, the file of which was extended (if it changes) won't trigger a compiling
     *           again meaning that you'd then need to clear the view cache which is non-desirable so this will need
     *           to change somewhere; either that or have a compiled file name based on the view.
     *
     * @issue -> Currently this particular class and method is heavily depending on a filesystem existing within the
     *           application; so this might potentially be a better thing should this particular class utilise default
     *           php methods in order for getting the file content, rather than being heavily intertwined and
     *           interlocked together... making it less modular all around.
     *
     *
     * @param View $view
     * @return Response
     */
    public function render(View $view): Response
    {
        $this->cache = $view->getHashedTemplate();

        $this->cacheComponents[$this->cache][] = $view->getTemplate();

        $cache = $this->manager->getStoragePath(
            _String::fromString($view->getHashedTemplate())->append('.php')
        );

        if ($this->shouldRecompile($cache, $view)) {
            // Acquire the original content from the view that has been passed in.
            $originalContent = $this->getViewManager()->getFilesystem()->get($view->template);
            // Pre-compile the original content which will look for key-words, like yield, extends and begin stripping
            // and making sections and blocks within the engine, so that they can be mapped accordingly to its' main
            // template.
            $precompiled = $this->preCompile($originalContent);
            // compile the content finally, which will begin attaching all the above yields, sections and layouts to
            // the final template and then dump them into a cached PHP file.
            $compiled = $this->compile($precompiled);
            // place the contents that has been compiled into storage so that we can simply acquire the file from the
            // build and then execute the PHP.
            $this->getViewManager()->getFilesystem()->put($cache, $compiled);
            // put the components to file so that we can reference this at a later time - utilised for the sake of
            // knowing whether something within the file stack has been changed or not.
            $this->putCompiledComponentsToFile();
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
        $content = preg_replace_callback($this->compilerRegex['section'], function ($matches) {
            $this->yields[$matches[2]] = $matches[0];
        }, $content);

        $content = preg_replace_callback($this->compilerRegex['extends'], function ($matches) {
            // here we are going to look for a file that has been matched within the extends...
            $extendContentPath = $this->getViewManager()->getResourcePath("$matches[1].vyui.php");

            $this->cacheComponents[$this->cache][] = $extendContentPath;

            return $this->layouts[$matches[1]] = $this->getViewManager()->getFilesystem()->get($extendContentPath);
        }, $content);

        $content = preg_replace_callback($this->compilerRegex['include'], function ($matches) use ($content) {
            $includeContentPath = $this->getViewManager()
                                       ->getResourcePath("$matches[1].vyui.php");

            $this->cacheComponents[$this->cache][] = $includeContentPath;

            // get the content of the include file so that we can pull it in to the cache file.
            $includeContent = $this->getViewManager()->getFilesystem()->get($includeContentPath);

            // find the spaces that exist before the include so that this can be stored and applied later to each
            // individual line in order for true indentation within the cache files.
            $matchPiece = str_replace('/', '\/', $matches[1]);
            $regex = "( +)(#\[include: $matchPiece\])";
            preg_match("/$regex/", $content, $includeContentMatches);

            // look for all line breaks and on each one apply the spacing that had been found within the initial
            // so that when the cached file is created the right indentations are applied making the cache more
            // readable should there ba any need for debugging errors.
            return $this->layouts[$matches[1]] = preg_replace_callback(
                '/\n(.*)/',
                function ($matches) use ($includeContentMatches) {
                    return str_replace("\n", "\n$includeContentMatches[1]", $matches[0]);
                },
                $includeContent
            );
        }, $content);

        return preg_replace_callback_array([
            $this->compilerRegex['yield'] => function ($matches) {
                if (! isset($this->yields[$matches[2]])) {
                    return '';
                }

                $space = $matches[1];

                // here we are going to do a bit of jiggery pokery to sort out the layout of the created cached file,
                // even though this is worthless, the presentation of files just... has to be right up there.
                return preg_replace_callback('/(.*)[\s\S]/', function ($matches) use ($space) {
                    return $space . $matches[0];
                }, $this->yields[$matches[2]]);
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

    /**
     * @issue -> this is just a code separator to let the user know that there was a yielding to the content that has
     *           been started.
     * @todo  -> offer this a sense of functionality or remove from the code and potentially just dump comments to the
     *           cached file to let the developer know where a segment had started.
     *
     * @return void
     */
    public function startSection(): void
    {

    }

    /**
     * @issue -> this is just a code separator to let the developer know that there was a yielding to the content and
     *           was ended.
     * @todo ->  offer this a sense of functionality or remove from the code and potentially dump comments to the cached
     *           file to let the developer know where a segment has been ended.
     *
     * @return void
     */
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

    /**
     * Cache the current component pieces into a file that we can then later look for when we're in need of being able
     * to decide whether a particular build needs re-compiling or not.
     *
     * @return void
     */
    private function putCompiledComponentsToFile(): void
    {
        // if we don't have more than one component in the tree of files that took this particular file to build then
        // we are going to return early otherwise the rest of the code will be unnecessary to the process.
        if (count($this->cacheComponents[$this->cache]) <= 1) {
            return;
        }

        // build the current component tree for this particular file; however this only really wants to happen when
        // there has been a need for it... such as having components bigger than the necessary.
        $this->getViewManager()->getFilesystem()->makeDirectory(
            $this->getViewManager()->getStoragePath('/builds')
        );

        // Acquire the build path as well as what is going into the build path, which can be utilised later in the
        // process of building the template which will help with later deciding whether the template wants re
        // compiling.
        $cacheComponentsPath = $this->getViewManager()->getStoragePath("/builds/{$this->cache}.php");
        $cacheComponentsContent = _String::fromString("<?php return [\n")
            ->append("    // This file {$this->cache}.php was generated utilising the following files: \n")
            ->append('    "')
            ->append(join("\",\n    \"", $this->cacheComponents[$this->cache]))
            ->append("\"\n")
            ->append('];');

        $this->getViewManager()->getFilesystem()->put($cacheComponentsPath, $cacheComponentsContent);
    }

    /**
     * Find out whether the particular view needs to be re compiled or not.
     *
     * @param string $cache
     * @param View $view
     * @return bool
     */
    private function shouldRecompile(string $cache, View $view): bool
    {
        if (! file_exists($cache) || filemtime($view->template) > filemtime($cache)) {
            return true;
        }

        $exists = $this->getViewManager()->getFilesystem()->exists(
            $path = $this->getViewManager()->getStoragePath("/builds/$this->cache.php")
        );

        // todo -> Find a nicer way to write this because I hate the way that this is currently written and how it's
        //         looking; doesn't look the most intuitive.
        if ($exists) {
            foreach (include($path) as $file) {
                if (filemtime($file) > filemtime($cache)) {
                    return true;
                }
            }
        }

        // if we've made it here then we really shouldn't be recompiling... and can just return the original file that
        // we had already built.
        return false;
    }
}
