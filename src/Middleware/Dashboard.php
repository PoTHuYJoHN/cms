<?php

namespace Webkid\Cms\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class Dashboard {

	/**
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Dashboard constructor.
	 *
	 * @param Guard $auth
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (!$this->auth->check() || !$this->auth->user()->isAdmin()) {
			return redirect(route('login'));
		}

		return $next($request);
	}

}
