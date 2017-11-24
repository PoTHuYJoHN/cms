<?php

namespace App\Http\Middleware;

use App;
use Closure;

/**
 * Class Language
 *
 * @package App\Http\Middleware
 */
class Language
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$locales = config('langs');
		$locale  = $request->cookie('lang');

		if (!is_null($locale) && is_array($locales) && in_array($locale, $locales)) {
			App::setLocale($locale);
		}

		return $next($request);
	}
}
