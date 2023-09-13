<?php

namespace Vyui\Foundation\Console\Commands;

use Throwable;
use Vyui\Tests\TestCase;
use Vyui\Tests\TestCollection;
use Vyui\Foundation\Application;
use Vyui\Support\Helpers\_String;
use Vyui\Support\Helpers\_Reflect;
use Vyui\Contracts\Filesystem\Filesystem;

class Test extends Command
{
    /**
     * @var Application
     */
    protected Application $application;

    /**
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * The path to the tests within the framework.
     *
     * @var string[]
     */
    protected array $paths = [];

    /**
     * @var int
     */
    protected int $passedAssertions = 0;

    /**
     * @var int
     */
    protected int $failedAssertions = 0;

    /**
     * @var int
     */
    protected int $totalAssertions = 0;

    /**
     * The TestCase collection which will contain all the test files that the developer is intending on having tested.
     *
     * @var TestCollection
     */
    protected TestCollection $tests;

    /**
     * @param Application $application
     * @param Filesystem $filesystem
     * @param array $arguments
     */
    public function __construct(Application $application, FileSystem $filesystem, array $arguments = [])
    {
        parent::__construct($arguments);

        $this->application = $application;
        $this->filesystem  = $filesystem;
        $this->paths[]     = $this->application->getBasePath('/tests');
        $this->tests       = new TestCollection;
    }

    /**
     * @return int
     */
    public function execute(): int
    {
        return $this->loadTests()
                    ->runTests();
    }

    /**
     * Run the tests that have been loaded into the suite.
     *
     * @return int
     */
    private function runTests(): int
    {
        foreach ($this->tests->all() as $test) {
            try {
                $classMethods = _Reflect::fromClass($test)->filterMethodsWhereContains('test')
                                                          ->getMethods();

                // print separator so that we know what we're working with, when talking about each particular test in
                // question. This is a way of knowing that the previous test assertions had ended now we're onto
                // a new set.
                $this->output->print("├──────────────────────────────────────────────────────────");

                // Let the developer know which test is getting run; this would be the overall class file that's
                // being run; so the developer would know which file the test has been run and if an error occurs
                // limits the place where they need to look.
                $this->output->printInfo("ⓘ Starting testing $test");

                foreach ($classMethods as $method) {
                    // Here we could actually do $test->{$method}() which will call the method directly however; This
                    // has been done so that we can group a bunch of code together and not have to keep re-implementing
                    // acquiring the test name which would take the name of the method; however since we already have
                    // it within the $method variable; apply once and then fire the original code that was intended.
                    $test->__call($method->getName());

                    // print to the developer that the specific test is being run, which if there happens to be an
                    // issue around this point then the developer will know exactly where to focus their efforts.
                    $this->output->print("ⓘ {$test->getTestName()}");

                    // iterate over all the assertions and print out what the status of the assertion is, since we have
                    // knowledge of the assertion whether it was successful or not... we can dump out the message here
                    foreach ($test->getAssertions($test->getTestName()) as $assertion) {
                        $this->output->print("├── ", false);
                        $assertion->getState() ? $this->output->printSuccess("✓ {$assertion->getMessage()}")
                                               : $this->output->printError("✗ {$assertion->getMessage()}");
                    }
                }

                // When having iterated over the tests assertions, we can begin counting the total number of assertions
                // whether that would be passed or failed as well as counting towards the total number of assertions
                // from all tests.
                $this->countAssertions($test);

                // print to the user, depending on whether the particular assertion had passed or failed; if it had
                // failed the developer would know exactly where to look.
                $test->wasSuccessful() ? $this->output->printSuccess($this->getTotalTestAssertionMessage($test))
                                       : $this->output->printError($this->getTotalTestAssertionMessage($test));
            }

            // If for some reason there was an issue in trying to reflect the class that we have (which there shouldn't
            // be), as we actually have the class implemented, in the loading; but we are going to print out that
            // something had gone wrong during the reflection.
            catch (Throwable $exception) {
                $this->output->print("there was an issue testing $test [{$exception->getMessage()}]");
            }
        }

        return 1;
    }

    /**
     * @param TestCase $test
     * @return string
     */
    private function getTotalTestAssertionMessage(TestCase $test): string
    {
        return "ⓘ $test [{$test->getPassedAssertions()}/{$test->getTotalAssertions()}]";
    }

    /**
     * @param TestCase $test
     * @return void
     */
    private function countAssertions(TestCase $test): void
    {
        $this->passedAssertions += $test->getPassedAssertions();
        $this->failedAssertions += $test->getFailedAssertions();
        $this->totalAssertions  += $test->getTotalAssertions();
    }

    /**
     * Use the file system and load in all the test files that reside within the tests directory; recursively acquire
     * them all and then dump them into the array for later running.
     *
     * @return $this
     */
    private function loadTests(): static
    {
        $files = [];

        // acquire all the test files from the filesystem so that we can begin iterating over them and loading in the
        // classes and apply them into memory to later iterate over and begin running over the tests that reside within
        // each test class.
        foreach ($this->paths as $path) {
            $files = array_merge($files, $this->filesystem->files($path, false));
        }

        $progress = $this->output->createProgress(count($files));

        $this->output->print('loading files...');

        foreach ($files as $key => $file) {
            // Get the particular test files from the directory of the tests; this could potentially be placed within a
            // testing service or something or other so that the user can customise where their tests are being loaded
            // from however this right now is just going to be firing up the testing files... this will be loaded
            // through the application so dependencies can be injected via the constructor as per needed to see how the
            // whole system would work with the dependency injector.
            $test = _String::fromString($file)->remove([$this->application->getBasePath('/'), '.php'])
                                              ->replace('/', '\\')
                                              ->ucFirst();

            // After loading the test file we can print out that the test had been successfully loaded and the
            // application had managed to make the test class.
            try {
                $this->tests->add($key, $this->application->make($test));
            }

            // If the application has failed to load the test, and something went wrong during the creation of, then
            // we're going to need to alert to the developer via the console that there was something wrong during
            // the process of creation; and dump out the message of the exception to the reader.
            catch (Throwable $exception) {
                $this->output->printError("✗ failed to load $test [{$exception->getMessage()}]");
            }

            $progress->advance();
        }

        return $this;
    }
}