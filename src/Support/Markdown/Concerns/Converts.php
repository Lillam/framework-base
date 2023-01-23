<?php

namespace Vyui\Support\Markdown\Concerns;

trait Converts
{
    protected function convertAll(): static
    {
        return $this->convertHeaders();
    }

	protected function convertHeaders(): static
	{
		for ($i = 1; $i <= 6; ++$i) {
			$this->content = preg_replace_callback("#(<h$i>)(.*)(</h$i>)#", function ($matches) use ($i) {
				return str_repeat('#', $i) . " $matches[2] \n";
			}, $this->content);
		}

		return $this;
	}
}