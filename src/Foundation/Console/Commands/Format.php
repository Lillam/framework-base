<?php

namespace Vyui\Foundation\Console\Commands;

use Vyui\Foundation\Application;
use Vyui\Support\Helpers\_String;
use Vyui\Services\Formatter\PhpFile;
use Vyui\Contracts\Filesystem\Filesystem;

class Format extends Command
{
    /**
     * Implement the application so that we have access to the core root of the project.
     *
     * @var Application
     */
    protected Application $application;

    /**
     * Implement the file system so that we can load every single file within the application.
     *
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * All the files in the actual application.
     *
     * @var array
     */
    protected array $files = [];

    /**
     * The particular errors that were captured for the indentations of the project. i.e...
     * function () {
     *          return 'here';
     * }
     *
     * @var array
     */
    protected array $indentationErrors = [];

    /**
     * The particular errors that were captured for the bracing. i.e... method() { instead of brace being on new line.
     *
     * @var array
     */
    protected array $bracePlacementErrors = [];

    /**
     * Directories of which are going to want to be ignored. Vendor based directives are not to be tampered with.
     *
     * @var array|string[]
     */
    protected array $ignoredDirectories = [
        'vendor',
        'storage'
    ];

    /**
     * An array of files of which we are going to explicitly want to ignore.
     *
     * @var array|string[]
     */
    protected array $ignoredFiles = [];

    /**
     * todo -> Modify this particular variable to utilise the new PHPFile that has been made within the codebase; which
     *         will be utilised in order for appending to a file. Which will iterate over the build of a particular PHP
     *         file.
     *         <?php
     *         blank line
     *         namespace of the file
     *         blank line
     *         imports (which would be stored as a parameter inside the file $imports = []; which contains them...
     *                  meaning that the developer can decide how these want to be printed back to the file that we're
     *                  constructing... ordered by length, alphabetically, ascending descending etc...)
     *         methods (which would be stored as a parameter inside the file $methods = []; which contains them...
     *                  meaning that the developer can decide how these want to be printed back, whether these want to
     *                  to be organised by publicity (private, protected, static, public etc)
     *
     * The pieces of the file that will be getting fixed; that will need building so that we can then later apply this
     * to be submitted into the filesystem at a later point. (this is so that we can organise accordingly.
     *
     * @var PhpFile
     */
    protected PhpFile $currentFile;

    /**
     * @param Application $application
     * @param Filesystem $filesystem
     * @param array $arguments
     */
    public function __construct(Application $application, FileSystem $filesystem, array $arguments = [])
    {
        parent::__construct($application, $arguments);
        $this->filesystem = $filesystem;
        $this->application = $application;
    }

    /**
     * @return int
     */
    public function execute(): int
    {
        $this->loadAllProjectFiles();
        $this->sortImportOrder();
        // $this->fixAllProjectFiles();
        $this->output->print(count($this->indentationErrors) . ' files have indentation errors fixed');

        return 1;
    }

    /**
     * Get the ignored directories in the form of a regex string.
     *
     * @return string
     */
    private function getIgnoredProjectDirectoriesRegex(): string
    {
        $regex = implode('|', array_map(fn ($ignore) => "(\/$ignore.*)", $this->ignoredDirectories));

        return "/$regex/";
    }

    /**
     * Get the ignored files in the form of a regex string.
     *
     * @return string
     */
    private function getIgnoredProjectFilesRegex(): string
    {
        $regex = implode('|', array_map(fn ($ignore) => "(\/$ignore.*)", $this->ignoredFiles));

        return "/$regex/";
    }

    /**
     * Load all files that reside within the applications root directory; this is so that we can begin formatting all
     * the code of the project.
     *
     * @return void
     */
    private function loadAllProjectFiles(): void
    {
        $files = $this->filesystem->files($this->application->getBasePath(), false);

        $this->files = array_filter($files, fn (string $file) => $this->filterProjectFiles($file));

        // print out the total number of files that have been loaded to fix based on the number of files that had been
        // implemented via the filtering above.
        $this->output->printSuccess(count($this->files) . ' files have been loaded');
    }

    /**
     * @param string $file
     * @return bool
     */
    private function filterProjectFiles(string $file): bool
    {
        $directoryMatches = null;
        $fileMatches      = null;

        // first we have to check if we have any ignored directories, and if we have then we need to match it against
        // the particular passed file; if the directory exists within then we can just ignore this particular file.
        if ($this->ignoredDirectories) {
            preg_match($this->getIgnoredProjectDirectoriesRegex(), $file, $directoryMatches);
        }

        // first we have to check if we have any ignored files, and if we have then we need to match it against the
        // particular passed file; if the file exists within then we can just ignore this particular file.
        if ($this->ignoredFiles) {
            preg_match($this->getIgnoredProjectFilesRegex(), $file, $fileMatches);
        }

        preg_match('/.+[a-zA-Z0-9]/', $file, $isFile);

        dd($isFile);

        return ! $directoryMatches &&
               ! $fileMatches &&
               $isFile;
//               str_contains($file, '.php');
    }

    /**
     * Increment the amount of errors that had been found within indentations.
     *
     * @param string $file
     * @return void
     */
    private function incrementIndentationErrors(string $file): void
    {
        if (! isset($this->indentationErrors[$file])) {
            $this->indentationErrors[$file] = 1;
            return;
        }

        $this->indentationErrors[$file] += 1;
    }

    private function sortImportOrder(): void
    {
        foreach ($this->files as $file) {
            $fo = $this->filesystem->open($file);
            unset($fo);
        }
    }

    /**
     * Iterate over each file and begin checking to see whether they're in need of fixing or not...
     *
     * @return void
     */
    private function fixAllProjectFiles(): void
    {
        foreach ($this->files as $file) {
            $placingBack = preg_replace_callback_array([
                // look for the particular tab indent and replace it for 4 spaces instead; this would fix up the
                // random spacing caused within github as well as within the project. this particular snippet should be
                // ignored upon the actual formatter coming to format the formatter; wild.
                "/((?!\))	(?!\())/" => function () use ($file): string {
                    $this->incrementIndentationErrors($file);
                    // todo -> Change repeating from static (4) to a constant variable somewhere that might reside in
                    //         the user's configuration somewhere along the lines so that this will format to the
                    //         developer's standards.
                    return _String::fromString(' ')->repeat(4);
                }
            ], $this->filesystem->get($file));

            // if this particular file has any errors of the sorts then we're going to be able to then decide to save
            // the file, if not then there's no real need to do anything of the sorts; and just skip past this
            // particular segment
            if (array_key_exists($file, $this->indentationErrors)) {
                $this->filesystem->put($file, $placingBack);
                $this->output->printSuccess(
                    "✓ Fixed up indentations for: [$file ({$this->indentationErrors[$file]})]"
                );
            }
        }

        // print out the amount of errors that had been found within each file, this will give a good gist as to how
        // this might be working overall
        foreach ($this->indentationErrors as $indentationError => $count) {
            $this->output->printInfo("[$count] errors inside $indentationError");
        }
    }
}