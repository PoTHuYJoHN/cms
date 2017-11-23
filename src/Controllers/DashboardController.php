<?php namespace Webkid\Cms\Controllers;

use Webkid\Cms\Controller;

class DashboardController extends Controller {

	public function index()
	{
		return view('cms::dashboard/main');
	}
}
