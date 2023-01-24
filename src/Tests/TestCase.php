<?php

namespace Vyui\Tests;

use Vyui\Support\Helpers\_String;
use Vyui\Tests\Assertions\AssertNull;
use Vyui\Tests\Assertions\AssertTrue;
use Vyui\Tests\Assertions\AssertCount;
use Vyui\Tests\Assertions\AssertEmpty;
use Vyui\Tests\Assertions\AssertFalse;
use Vyui\Tests\Assertions\AssertEquals;
use Vyui\Tests\Assertions\AssertNotNull;
use Vyui\Tests\Assertions\TestAssertion;
use Vyui\Tests\Assertions\AssertLessThan;
use Vyui\Tests\Assertions\AssertNotEmpty;
use Vyui\Tests\Assertions\AssertInstanceOf;
use Vyui\Tests\Assertions\AssertGreaterThan;
use Vyui\Tests\Assertions\AssertLooseEquals;

abstract class TestCase
{
    /**
     * The tests in which have passed.
     *
     * @var TestAssertionCollection
     */
    protected TestAssertionCollection $assertions;

    /**
     * The total number of assertions that had been successful, this would give the display of having 10/10 assertions
     *
     * @var int
     */
    protected int $successfulAssertions = 0;

    /**
     * The total number of assertions that had failed, this would give the display of having 6/10 assertions
     *
     * @var int
     */
    protected int $failedAssertions = 0;

    /**
     *
     *
     * @var int
     */
    protected int $totalAssertions = 0;

    /**
     * Upon object instantiation we're going to construct this particular item with the necessary collection items.
     *
     * @return void
     */
    public function __construct()
    {
        $this->assertions = new TestAssertionCollection;
    }

    /**
     * The name of the test that we're running, which would be the name of the testing method running at the time.
     *
     * @var string
     */
    protected string $testName = '';

    /**
     * Assert the two passed values equate to being one another.
     *
     * @param mixed $expected
     * @param mixed $actual
     * @return void
     */
    public function assertEquals(mixed $expected, mixed $actual): void
    {
        $this->processAssertion(new AssertEquals($expected, $actual));
    }

    /**
     * Assert that the two passed values equate to being one another loosely
     *
     * @param mixed $expected
     * @param mixed $actual
     * @return void
     */
    protected function assertLooseEquals(mixed $expected, mixed $actual): void
    {
        $this->processAssertion(new AssertLooseEquals($expected, $actual));
    }

    /**
     * Assert that the passed expected value is greater than the actual value.
     *
     * @param int $expected
     * @param int $actual
     * @return void
     */
    public function assertGreaterThan(int $expected, int $actual): void
    {
        $this->processAssertion(new AssertGreaterThan($expected, $actual));
    }

    /**
     * Assert that the pass expected value is less than the actual value.
     *
     * @param int $expected
     * @param int $actual
     * @return void
     */
    public function assertLessThan(int $expected, int $actual): void
    {
        $this->processAssertion(new AssertLessThan($expected, $actual));
    }

    /**
     * Assert that an array has the count of the expected pass parameter.
     *
     * @param int $expected
     * @param array $actual
     * @return void
     */
    public function assertCount(int $expected, array $actual): void
    {
        $this->processAssertion(new AssertCount($expected, $actual));
    }

    /**
     * Assert that the value us empty
     *
     * @param array $expected
     * @return void
     */
    public function assertEmpty(array $expected): void
    {
        $this->processAssertion(new AssertEmpty($expected));
    }

    /**
     * Assert that the value is not empty.
     *
     * @param array $expected
     * @return void
     */
    public function assertNotEmpty(array $expected): void
    {
        $this->processAssertion(new AssertNotEmpty($expected));
    }

    /**
     * Assert that the parameter is true
     *
     * @param bool $expected
     * @return void
     */
    public function assertTrue(bool $expected): void
    {
        $this->processAssertion(new AssertTrue($expected));
    }

    /**
     * Assert the expected value is false.
     *
     * @param bool $expected
     * @return void
     */
    public function assertFalse(bool $expected): void
    {
        $this->processAssertion(new AssertFalse($expected));
    }

    /**
     * Assert that the expected value is null
     *
     * @param mixed $expected
     * @return void
     */
    public function assertNull(mixed $expected): void
    {
        $this->processAssertion(new AssertNull($expected));
    }

    /**
     * Assert that the expected value is not null
     *
     * @param mixed $expected
     * @return void
     */
    public function assertNotNull(mixed $expected): void
    {
        $this->processAssertion(new AssertNotNull($expected));
    }

    /**
     * Assert that the expected value is an instanceof actual.
     *
     * @param string $expected
     * @param string $actual
     * @return void
     */
    public function assertInstanceOf(mixed $expected, string $actual): void
    {
        $this->processAssertion(new AssertInstanceOf($expected, $actual));
    }

    /**
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return ! $this->wasUnSuccessful();
    }

    /**
     * @return bool
     */
    public function wasUnsuccessful(): bool
    {
        return $this->failedAssertions > 0;
    }

    /**
     * Get the total number of assertions that would have been made during this test.
     *
     * @return int
     */
    public function getTotalAssertions(): int
    {
        return $this->totalAssertions;
    }

    /**
     * Get the passed tests for the particular test in question.
     *
     * @return TestAssertion[]
     */
    public function getAssertions(): array
    {
        return $this->assertions->all();
    }

    /**
     * Get the name of the test so that we can print out accordingly what test is being execited.
     *
     * @return string
     */
    public function getTestName(): string
    {
        return $this->testName;
    }

    /**
     * Run a test method dynamically so that we can universalise the way that the tests are reporting.
     *
     * @param string $method
     * @param array $parameters
     * @return void
     */
    public function __call(string $method, array $parameters = []): void
    {
        // first things first within this method we're going to want to figure out what the method's name is based on
        // it's defined name such as testSomethingIsActuallyTrue would want to be output as:
        // Test Something Is Actually True -> 3 assertions 3 passes check mark or
        // Test Something IS Atually True -> 3 assertions 2 passes and 1 fail cross mark.
        $this->testName = _String::fromString($method)->convertCamelCaseToSentence();

        // call the expected method that the developer had originally intended.
        $this->{$method}();
    }

    /**
     * Process the assertion that we're trying to make, this is so that we can begin accordingly map them to the correct
     * places and log only once and minimalising the amount of code overall needed
     *
     * @param TestAssertion $assertion
     * @return void
     */
    private function processAssertion(TestAssertion $assertion): void
    {
        $this->assertions->add($this->totalAssertions += 1, $assertion);

        if ($assertion->evaluate()) {
            $this->successfulAssertions += 1;
            return;
        }

        $this->failedAssertions += 1;
    }

    /**
     * When this particular class is cast to a string we are simply going to return the name of the class.
     *
     * @return string
     */
    public function __toString(): string
    {
        return static::class;
    }
}