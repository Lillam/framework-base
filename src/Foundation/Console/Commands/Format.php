<?php

namespace Vyui\Foundation\Console\Commands;

use Exception;
use Vyui\Foundation\Application;
use Vyui\Contracts\Filesystem\Filesystem;
use Vyui\Support\Helpers\_String;

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
     * The pieces of the file that will be getting fixed; that will need building so that we can then later apply this
     * to be submitted into the filesystem at a later point. (this is so that we can organise accordingly.
     *
     * @var string[]
     */
    protected array $currentlyFixingFile = [];

    /**
     * @param Application $application
     * @param Filesystem $filesystem
     * @param array $arguments
     */
    public function __construct(Application $application, FileSystem $filesystem, array $arguments = [])
    {
        parent::__construct($arguments);
        $this->filesystem = $filesystem;
        $this->application = $application;
    }

    /**
     * @return int
     */
    public function execute(): int
    {
        $this->loadAllProjectFiles();
        $this->fixAllProjectFiles();
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

        return ! $directoryMatches &&
               ! $fileMatches &&
               str_contains($file, '.php');
    }

    /**
     * Iterate over each file and begin checking to see whether they're in need of fixing or not...
     *
     * @return void
     */
    private function fixAllProjectFiles(): void
    {
        foreach ($this->files as $file) {
//            $fo = $this->filesystem->open($file);
//            $fix = _String::fromString('');
//            while (! $fo->eof()) {
//                // todo ->
//                //      start of file
//                //      must be one space between
//                //      namespace
//                //      must be one space between
//                //      use of imports
//                //      must be one space between
//                //      is class
//                //          does class have brace on the end or below - if on the end strip it off and move it below
//                $fix->append($fo->current());
//                $this->currentlyFixingFile[] = str_replace("\n", '', $fo->current());
//
//                // iterate to the next line in the file unless we have hit the end of the file...
//                $fo->next();
//            }

            $fileContents = $this->filesystem->get($file);

            $placingBack = preg_replace_callback_array([
                // look for the particular tab indent and replace it for 4 spaces instead; this would fix up the
                // random spacing caused within github as well as within the project. this keeps your format appropriate
                "/((?!\))	(?!\())/" => function () use ($file) {
                    if (! isset($this->indentationErrors[$file])) {
                        $this->indentationErrors[$file] = 1;
                    } else {
                        $this->indentationErrors[$file] += 1;
                    }

                    return '    ';
                }
            ], $fileContents);

            // if this particular file has any errors of the sorts then we're going to be able to then decide to save
            // the file, if not then there's no real need to do anything of the sorts; and just skip past this
            // particular segment
            if (isset($this->indentationErrors[$file])) {
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