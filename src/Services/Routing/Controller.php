<?php

namespace Vyui\Services\Routing;

use Exception;
use Vyui\Foundation\Http\Response;

abstract class Controller
{
	/**
	 * The default method that would be called when the controller is simply invoked.
	 * (new controller)() for example would simply call on the setup controller (new controller)->default() requiring a
	 * public function default() on the controller in question.
	 *
	 * @var string
	 */
	protected static string $defaultMethod = 'index';

	/**
	 * Set a default action when invoking a controller... this will call a default method on a controller class.
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function __invoke(): Response
	{
		if (method_exists($class = static::class, self::$defaultMethod)) {
			return $this->{static::$defaultMethod}();
		}

		throw new Exception("Default invocation method is not set on [$class]");
	}
}