<?php

namespace Webkid\Cms;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected $lang;

//	public function __construct()
//	{
////		$this->lang = \Session::get('locale', LANG_EN);
//	}

	public function getLang()
	{
		return $this->lang;
	}
}
