<?php
namespace Webkid\Cms\Traits;
use Webkid\Cms\File;
use Webkid\Cms\Repositories\FileRepository;

/**
 * Class FileSize
 *
 * @package App\Traits
 */
trait FileDimensions
{
	/**
	 * Get real file dimensions
	 * @param File $file
	 * @return array
	 */
	public function getImageDimensions(File $file)
	{
		$fileRepository = new FileRepository;
		$dimensions = [];
		try {
			$sizes = $fileRepository->getSizes($file->type);

			foreach($sizes as $sizeName => $value) {
				$realUrl = $fileRepository->getRealUrl($file, true, $sizeName);

				$dm = getimagesize($realUrl);

				if($dm) {
					$dimensions[] = [
						'type' => $sizeName,
						'width' => $dm[0],
						'height' => $dm[1]
					];
				}
			}



		} catch(\Exception $e) {}

		return $dimensions;
	}
}
