<?php

namespace App\Http\Controllers;

use Cookie;

/**
 * Class LangController
 *
 * @package App\Http\Controllers
 */
class LangController extends Controller
{
	/**
	 * @param $lang
	 * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function setLang($lang)
	{
		if (in_array($lang, config('langs'))) {
			return redirect('/')->withCookies(['lang' => Cookie::forever('lang', $lang)]);
		}

		return redirect('/');
	}
}
