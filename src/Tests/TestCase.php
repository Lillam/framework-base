<?php

namespace Vyui\Tests;

use BadMethodCallException;
use Vyui\Tests\Assertions\{
    AssertInt,
    AssertNull,
    AssertTrue,
    AssertCount,
    AssertEmpty,
    AssertArray,
    AssertFalse,
    AssertFloat,
    AssertEquals,
    AssertString,
    AssertNotNull,
    AssertNotEmpty,
    AssertLessThan,
    AssertInstanceOf,
    AssertGreaterThan,
    AssertLooseEquals,
    AssertArrayHasKey,
    AssertArrayNotHasKey
};
use Vyui\Support\Helpers\_String;
use Vyui\Tests\Assertions\TestAssertion;

// todo | clean this class up wit the use of traits, meaning that the assertion of the type would be a trat that gets
//      | applied to the class, which naturally would clean the above up; removing the needs of imports and more focuses
//      | on what the test case is aiming to do.

abstract class TestCase
{
    protected string $testName;

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
    protected int $passedAssertions = 0;

    /**
     * The total number of assertions that had failed, this would give the display of having 6/10 assertions
     *
     * @var int
     */
    protected int $failedAssertions = 0;

    /**
     * The total number of assertions that have been run.
     *
     * @var int
     */
    protected int $totalAssertions = 0;

    /**
     * Upon object instantiation we're going to construct this particular item with the necessary collection items.
     *
     * @param TestAssertionCollection|null $assertions
     * @return void
     */
    public function __construct(?TestAssertionCollection $assertions)
    {
        $this->assertions = $assertions ?? new TestAssertionCollection();
    }

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
     * Assert that the expected value exists as a key against the actual.
     *
     * @param string|int $expected
     * @param array $actual
     * @return void
     */
    public function assertArrayHasKey(string|int $expected, array $actual): void
    {
        $this->processAssertion(new AssertArrayHasKey($expected, $actual));
    }

    /**
     * Assert that the expected value does not exist as a key against the actual.
     *
     * @param string|int $expected
     * @param array $actual
     * @return void
     */
    public function assertArrayHasNotKey(string|int $expected, array $actual): void
    {
        $this->processAssertion(new AssertArrayNotHasKey($expected, $actual));
    }

    /**
     * @param mixed $expected
     * @return void
     */
    public function assertIsInt(mixed $expected): void
    {
        $this->processAssertion(new AssertInt($expected));
    }

    /**
     * @param mixed $expected
     * @return void
     */
    public function assertIsFloat(mixed $expected): void
    {
        $this->processAssertion(new AssertFloat($expected));
    }

    /**
     * @param mixed $expected
     * @return void
     */
    public function assertIsString(mixed $expected): void
    {
        $this->processAssertion(new AssertString($expected));
    }

    /**
     * @param mixed $expected
     * @return void
     */
    public function assertIsArray(mixed $expected): void
    {
        $this->processAssertion(new AssertArray($expected));
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
     * @return int
     */
    public function getPassedAssertions(): int
    {
        return $this->passedAssertions;
    }

    /**
     * @return int
     */
    public function getFailedAssertions(): int
    {
        return $this->failedAssertions;
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
     * @param int|string|null $key
     * @return TestAssertion[]
     */
    public function getAssertions(int|string $key = null): array
    {
        return $this->assertions->all($key);
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
        // Test Something Is Actually True -> 3 assertions 2 passes and 1 fail cross mark.
        $this->testName = _String::fromString($method)->convertCamelCaseToSentence();

        // if we're attempting to call a method that doesn't exist within the class then we're going to want to throw an
        // exception to the developer so that they can see that they're trying to call a method that doesn't exist.
        if (! method_exists($this, $method)) {
            throw new BadMethodCallException("Method [$method] does not exist within " . static::class);
        }

        // call the expected method that the developer had originally intended.
        $this->{$method}(...$parameters);
    }

    /**
     * Process the assertion that we're trying to make, this is so that we can begin accordingly map them to the correct
     * places and log only once and minimalistic with the amount of code overall needed
     *
     * @param TestAssertion $assertion
     * @return void
     */
    private function processAssertion(TestAssertion $assertion): void
    {
        // evaluate the assertion and then upon doing so set the state of the assertion to the evaluation (true|false)
        // so that we can then later utilise this result
        $assertion->setState($assertion->evaluate());

        $this->assertions->add($this->getTestName(), array_merge(
            $this->assertions->get($this->getTestName(), []),
            [$assertion]
        ));

        $this->incrementAssertions($assertion);
    }

    /**
     * A method in which will increment the counts of passed/failed assertions as well as the total number of assertions
     * that has been made within the test case.
     *
     * @param TestAssertion $assertion
     * @return void
     */
    private function incrementAssertions(TestAssertion $assertion): void
    {
        $this->totalAssertions += 1;

        $assertion->getState() ? $this->passedAssertions += 1
                               : $this->failedAssertions += 1;
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
