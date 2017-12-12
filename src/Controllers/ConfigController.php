<?php

namespace Webkid\Cms\Controllers;

use Webkid\Cms\Repositories\FileRepository;

class ConfigController extends ApiController
{
	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index()
	{
		$files = config('files');

		$files['sizes'] = FileRepository::getAllSizes();

		return $this->respond([
			'ENV' => app()->environment(),
			'files' => $files,
			'CSRF_TOKEN' => csrf_token(),
			'langs' => config('langs'),
			'pages' => config('pages'),
			'storage_url'  => env('STORAGE_URL'),
			'user' => auth()->user()
		]);
	}
}
