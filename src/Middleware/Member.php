<?php

namespace Webkid\Cms\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Member {
	/**
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Member constructor.
	 *
	 * @param Guard $auth
	 */
	function __construct(Guard $auth)
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
		if(! $this->auth->user()->isMember()) {
			return response('Unauthorized.', 401);
		}

		return $next($request);
	}

}
