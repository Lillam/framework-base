<?php

namespace Vyui\Foundation\Console\Commands;

use Throwable;
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
     * @var string
     */
    protected string $testsPath = '';

    /**
     * The TestCase collection which will contain all of the test files that the developer is intending on having
     * tested.
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
        $this->testsPath   = $this->application->getBasePath('/tests');
        $this->tests       = new TestCollection;
    }

    public function execute(): int
    {
        $this->loadTests();
        $this->runTests();

        return 1;
    }

    /**
     * Run the tests that have been loaded into the suite.
     *
     * @return void
     */
    private function runTests(): void
    {
        foreach ($this->tests->all() as $test) {
            try {
                $classMethods = _Reflect::fromClass($test)->filterMethodsWhereContains('test')
                                                          ->getMethods();

                // print separator so that we know what we're working with, when talking about each particular test in
                // question. This is a way of knowing that the previous test assertions had ended and now we're onto
                // a new set.
                $this->print("______________________________________________\n");

                foreach ($classMethods as $method) {
                    // Here we could actually do $test->{$method}() which will call the method directly however; This
                    // has been done so that we can group a bunch of code together and not have to keep re-implementing
                    // acquiring the test name which would take the name of the method; however since we already have
                    // it within the $method variable; apply once and then fire the original code that was intended.
                    $test->__call($method->getName());

                    // Let the developer know which test is getting run; this would be the overall class file that's
                    // being run; so the developer would know which file the test has been run and if an error occurrs
                    // limits the place where they need to look.
                    $this->printInfo("Starting testing $test");

                    // print to the devleoper that the specific test is being run, which if there happens to be an issue
                    // around this point then the developer will know exactly where to focus their efforts.
                    $this->print($test->getTestName());

                    // iterate over all the assertions and print out what the status of the assertion is, since we have
                    // knowledge of the assertion whether it was successful or not... we can dump out the message here
                    foreach ($test->getAssertions() as $assertion) {
                        $assertion->getState() ? $this->printSuccess("✓ {$assertion->getMessage()}")
                                               : $this->printError("✗ {$assertion->getMessage()}");
                    }
                }

                // print to the user, depending on whether the particular assertion had passed or failed; if it had
                // failed the developer would know exactly where to look.
                $test->wasSuccessful() ? $this->printSuccess("✓ $test")
                                       : $this->printError("✗ $test");
            }

            // If for some reason there was an issue in trying to reflect the class that we have (which there shouldn't
            // be), as we actually have the class implemented, in the loading; but we are going to print out that
            // something had gone wrong during the reflection.
            catch (Throwable $exception) {
                $this->print("there was an issue testing $test {$exception->getMessage()}");
            }
        }
    }

    /**
     * Use the file system and load in all the test files that reside within the tests directory; recursively acquire
     * them all and then dump them into the array for later running.
     *
     * @return void
     */
    private function loadTests(): void
    {
        $this->printInfo('Tests loading...');

        // acquire all the test files from the filesystem so that we can begin iterating over them and loading in the
        // classes and apply them into memory to later iterate over and begin running over the tests that reside within
        // each test class.
        $files = $this->filesystem->files($this->testsPath, false);

        foreach ($files as $key => $file) {
            // Get the particular test files from the directory of the tests; this could potentially be placed within a
            // testing service or something or other so that the user can customise where their tests are being loaded
            // from however this right now is just going to be firing up the testing files... this will be loaded
            // through the application so dependencies can be injected via the constructor as per needed to see how the
            // whole system would work with the dependency injector.
            $test = _String::fromString($file)->remove([$this->application->getBasePath('/'), '.php'])
                                              ->replace('/', '\\')
                                              ->ucFirst();

            $this->tests->add($key, $this->application->make($test));

            // todo attempt to capture the fact that a test file could be failed to be made and we can alert on this
            //      here to the developer to let them know that something needs fixing.
            $this->printSuccess("✓ $test");
        }

        $this->printSuccess('Tests loaded...');
    }
}