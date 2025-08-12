<?php

namespace Smashballoon\ClickSocial\App\Services\InertiaAdapter;

if (!defined('ABSPATH')) exit;

class LazyProp
{
	protected $callback;

	public function __construct(callable $callback)
	{
		$this->callback = $callback;
	}

	public function __invoke()
	{
		return call_user_func($this->callback);
	}
}
