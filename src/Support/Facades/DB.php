<?php

namespace Vyui\Support\Facades;

use Vyui\Contracts\Database\ConnectionManagerInterface;
use Vyui\Services\Facades\Facade;

class DB extends Facade
{
	/**
	 * @return string
	 */
	protected static function getFacadeAccessor(): string
	{
		return ConnectionManagerInterface::class;
	}
}