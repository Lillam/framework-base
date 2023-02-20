<?php

namespace Vyui\Support\Helpers;

class Stringable
{
    /**
     * The string of which will be objectified; and returned content upon this being cast to a string.
     *
     * @var string
     */
    protected string $string;

    /**
     * @param string $string
     */
    public function __construct(string $string)
    {
        $this->string = $string;
    }

    /**
     * Append a chosen string at the end of the currently existing string against the object.
     *
     * @param string $string
     * @return $this
     */
    public function append(string $string): static
    {
        $this->string = "{$this->string}$string";

        return $this;
    }

    /**
     * Prepend a chosen string at the beginning of the currently existing string against the object.
     *
     * @param string $string
     * @return $this
     */
    public function prepend(string $string): static
    {
        $this->string = "$string {$this->string}";

        return $this;
    }

    /**
     * Concatenate the set strings passed to this method onto the string that's stored against the object.
     *
     * @param ...$strings
     * @return $this
     */
    public function concat(...$strings): static
    {
        foreach ($strings as $string) {
            $this->string = "{$this->string} $string";
        }

        return $this;
    }

    /**
     * Turn a string into snake case, ensuring that everything is lowercased for instance:
     * getProject would be malformed into get_project
     *
     * @return $this
     */
    public function snakecase(): static
    {
        $this->string = strtolower(implode(
            '_',
            preg_split('/(?=[A-Z])/', lcfirst($this->string))
        ));

        return $this;
    }

    /**
     * Turn a string into a slug that will hyphenate a space separated string and turn it into something that can be
     * recognised as a uri.
     *
     * @param array $replaceDictionary  a replacement dictionary for what wants to be sluggified in place of.
     * @return $this
     */
    public function slug(array $replaceDictionary = []): static
    {
        if (! in_array(' ', $replaceDictionary)) {
            $replaceDictionary[' '] = '-';
        }

        $this->string = str_replace(
            array_keys($replaceDictionary),
            array_values($replaceDictionary),
            $this->string
        );

        return $this->toLower();
    }

    /**
     * Remove defined from the string content.
     *
     * @param string|array $remove
     * @return $this
     */
    public function remove(string|array $remove): static
    {
        $this->string = str_replace($remove, '', $this->string);

        return $this;
    }

    /**
     * Take a value and replace it with something els from the defined string content.
     *
     * @param string $target
     * @param string $replace
     * @return $this
     */
    public function replace(string $target, string $replace): static
    {
        $this->string = str_replace($target, $replace, $this->string);

        return $this;
    }

    /**
     * Uppercase the first character within the string, this would uppercase the value at the point where the string
     * was modified up to.
     *
     * @return $this
     */
    public function ucFirst(): static
    {
        $this->string = ucfirst($this->string);

        return $this;
    }

    /**
     * Turn the current string into a lower case rendition of it.
     *
     * @return $this
     */
    public function toLower(): static
    {
        $this->string = mb_strtolower($this->string);

        return $this;
    }

    /**
     * Turn the current string into an upper case rendition of it.
     *
     * @return $this
     */
    public function toUpper(): static
    {
        $this->string = mb_strtoupper($this->string);

        return $this;
    }

    /**
     * Convert a Camel Case (camelCase) string to a sentence structuer such as converting: printHelloWorld would then
     * look more like Print hello world; which will help with programmatical definitions.
     *
     * @return $this
     */
    public function convertCamelCaseToSentence(): static
    {
        $this->string = preg_replace_callback('/([A-Z0-9])/', function (array $matches) {
            return " {$matches[0]}";
        }, $this->string);

        return $this->toLower()->ucFirst();
    }

    /**
     * Convert a sentence that has been structured and make it camel case; i.e... take: Print Hello There to -
     * printHelloThere which can help further down the line with programmatical dynamics.
     *
     * @return $this
     */
    public function convertSentenceToCamelCase(): static
    {
        $this->string = preg_replace_callback('/ +([A-Za-z0-9])/', function (array $matches) {
            return trim(mb_strtoupper($matches[0]));
        }, lcfirst($this->string));

        return $this;
    }

    /**
     * Repeat the current state, (x) amount of times based on what the developer has decided to pass.
     *
     * @param int $repeating
     * @return $this
     */
    public function repeat(int $repeating = 0): static
    {
        $this->string = implode(array_fill(0, $repeating, $this->string));

        return $this;
    }

    /**
     * A publid method in which allows us to turn the object into just a string, this will essentially be the final
     * method that gets called before it's no longer operable as an object.
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->__toString();
    }

    /**
     * Return the string content of the stringable item... which when this object is cast to a string this is the way
     * in which it will be returned.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->string;
    }
}