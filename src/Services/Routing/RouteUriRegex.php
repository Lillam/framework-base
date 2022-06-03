<?php

namespace Vyui\Services\Routing;

class RouteUriRegex
{
	/**
	 * @var string
	 */
	protected string $regex;

	/**
	 * @param Route $route
	 */
	public function __construct(Route $route)
	{
		$this->regex = preg_replace_callback(
			'#{([^}]+)}#',
			function (array $matches) use ($route) {
				$route->addParameter(rtrim($matches[1], '?'));
				return str_ends_with($matches[1], '?')
					? '([^/]*)(?:/?)'
					: '([^/]+)';
			},
			$route->getNormalisedUri()
		);
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->regex;
	}
}