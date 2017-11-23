<?php

namespace Webkid\Cms\Http\Controllers;

use Webkid\Cms\Http\Requests;
use Webkid\Cms\Repositories\FileRepository;

class FilesController extends Controller {

	/**
	 * Generate download link for file with $token
	 *
	 * @param $token
	 * @param FileRepository $fileRepository
	 * @return mixed
	 */
	public function download($token, FileRepository $fileRepository)
	{
		$file = $fileRepository->getByToken($token);
		$path = $fileRepository->generateDownloadLink($file);

		return \Response::download($path, $file->name, [
			'Content-Length: '. $file->size
		]);

	}
}
