<?php

namespace Webkid\Cms\Http\Middleware;

use Closure;

class ComingSoonMode {

	private $_exceptPaths = ['apply', 'contact'];
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if(env('COMING_SOON') && !$this->isExceptPaths($request->path())) {
			if($request->path() != '/') {
				return redirect('/');
			}
		}

		return $next($request);
	}

	/**
	 * @param $path
	 *
	 * @return bool
	 */
	private function isExceptPaths($path)
	{
		return in_array($path, $this->_exceptPaths);
	}

}
