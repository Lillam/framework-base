<?php

namespace Vyui\Foundation\Console\Commands;

use Throwable;
use ReflectionMethod;
use Vyui\Tests\TestCase;
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
     * @var TestCase[]
     */
    protected array $tests = [];

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
        foreach ($this->tests as $test) {
            try {
                $classMethods = _Reflect::fromClass($test)->getMethods(filter: fn (ReflectionMethod $method) =>
                    str_contains($method->getName(), 'test')
                );

                $this->print("______________________________________________\n");

                foreach ($classMethods as $method) {
                    // $this->print("Running {$method->getName()} within $test");
                    // $test->{$method->getName()}();
                    $test->__call($method->getName());

                    $this->print(":info:Starting testing $test:end:");
                    $this->print($test->getTestName());

                    // iterate over all the assertions and print out what the status of the assertion is, since we have
                    // knowledge of the assertion whether it was successful or not... we can dump out the message here
                    foreach ($test->getAssertions() as $assertion) {
                        $this->print($assertion->getState() ? ":success:{$assertion->getMessage()} ✓:end:"
                                                                    : ":error:{$assertion->getMessage()} ✗:end:"
                        );
                    }
                }

                $this->print($test->wasSuccessful()
                    ? ":success:$test was Successful ✓:end:"
                    : ":error:$test was Unsuccessful ✗:end:"
                );
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
        $this->print(":success:Test files have begun loading...:end:");

        // acquire all the test files from the filesystem so that we can begin iterating over them and loading in the
        // classes and apply them into memory to later iterate over and begin running over the tests that reside within
        // each test class.
        $files = $this->filesystem->files($this->testsPath, false);

        foreach ($files as $file) {
            // Get the particular test files from the directory of the tests; this could potentially be placed within a
            // testing service or something or other so that the user can customise where their tests are being loaded
            // from however this right now is just going to be firing up the testing files... this will be loaded
            // through the application so dependencies can be injected via the constructor as per needed to see how the
            // whole system would work with the dependency injector.
            $test = _String::fromString($file)
                ->remove($this->application->getBasePath('/'))
                ->remove('.php')
                ->replace('/', '\\')
                ->ucFirst();

            $this->tests[] = $this->application->make($test);
            $this->print(":success:$test has been loaded:end:");
        }

        $this->print(":success:Test files have been loaded...:end:");
    }
}