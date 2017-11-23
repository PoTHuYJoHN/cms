<?php


namespace Webkid\Cms\Services\Image;


use Webkid\Cms\Events\Files\ImageToBlurWasUploaded;
use Webkid\Cms\Repositories\FileRepository;
use Log;
use Storage;

/**
 * Class Resizer
 *
 * @package App\Services\Image
 */
class Resizer
{
	/*
	 * Get url for converter lib.
	 * For example: /usr/bin/convert
	 */
	/**
	 * @var string
	 */
	protected $convert;

	/**
	 * Get convert path
	 */
	public function __construct()
	{
		$this->convert = isset($_ENV['LIB_IMAGE_CONVERT_PATH']) ? $_ENV['LIB_IMAGE_CONVERT_PATH'] . '/convert' : 'convert';
	}

	/**
	 * Creates image dimensions based on a configuration
	 *
	 * @param      $type
	 * @param      $token
	 * @param      $url
	 * @param bool $exactSize
	 * @param bool|array $cropArea
	 */
	public function createDimensions($type, $token, $url, $exactSize = false, $cropArea = false)
	{
		$dimensions = FileRepository::getSizes($type);

		foreach ($dimensions as $sizeName => $dimension) {
			if($exactSize && $sizeName !== $exactSize) {
				continue;
			}

			if($cropArea) {
				$dimension['type'] = 'crop_by_area';
				$dimension['cropArea'] = $cropArea[$sizeName];
			}

//			$this->resize($url, $type, $token, $sizeName, $dimension);

			if(isset($dimension['blur'])) {
				$realUrlOriginal = FileRepository::generateRealUrl($type, $token, $dimension['blur_original_size']);

				\Event::fire(new ImageToBlurWasUploaded( $realUrlOriginal, $type, $token, $sizeName, $dimension));
			} else {
				$this->resize($url, $type, $token, $sizeName, $dimension);
			}
		}
	}

	/**
	 * Resize an image
	 *
	 * @param  string $url
	 * @param         $fileType
	 * @param         $token
	 * @param         $sizeName
	 * @param         $options
	 *
	 * @return string
	 */
	public function resize($url, $fileType, $token, $sizeName, $options)
	{
		$width = isset($options['width']) ? $options['width'] : null;
		$height = isset($options['height']) ? $options['height'] : null;
		$resizeType = isset($options['type']) ? $options['type'] : null;
		$quality = isset($options['quality']) ? $options['quality'] : 90;
		$blur = isset($options['blur']) ? $options['blur'] : null;
		$format = $options['format'] ?: 'jpg';
		$cropArea = isset($options['cropArea']) ? $options['cropArea'] : false;
		$greyScale = isset($options['greyscale']) ? $options['greyscale'] : false;

		$targetDirectory = FileRepository::generatePath($fileType, $token, true);
//		$targetFile = $targetDirectory . DIRECTORY_SEPARATOR . $sizeName . '.' .$format;
//		$targetFile = $targetDirectory . $sizeName . '.' .$format;

//		$targetFile = DIRECTORY_SEPARATOR . $targetFile;

		$targetFile = FileRepository::generateRealUrl($fileType, $token, $sizeName, $format, true);

		try
		{
			// Create dir if missing
			Storage::makeDirectory($targetDirectory);
			$this->resizeImage($resizeType, $url, $width, $height, $targetFile, $quality, $blur, $cropArea, $greyScale);

		}
		catch (\Exception $e)
		{
			Log::error('[IMAGE SERVICE] Failed to resize image "' . $url . '" [' . $e->getMessage() . ']');
		}

		return $targetFile;
	}

	/**
	 * Resize image
	 *
	 * @param string $resizeType
	 * @param $sourceUrl
	 * @param $width
	 * @param $height
	 * @param $targetUrl
	 * @param $quality
	 * @param bool|false $blur
	 */
	private function resizeImage($resizeType = 'crop', $sourceUrl, $width, $height, $targetUrl, $quality = 90, $blur = false, $cropArea = false, $greyScale = false){

		$resolution = getimagesize($sourceUrl);
		$center = array(round($resolution[0] / 2), round($resolution[1] / 2));

		switch($resizeType) {

			case 'crop':

				if(!$height) {
					$height = $width;
				}

				$ratio = $this->ratio($resolution, $width, $height, 'crop');
				$cropArea = array($width / $ratio, $height / $ratio);
				$newResolution = array($width, $height);
				$offset = $this->offset($center, $resolution, $cropArea);
				$geometry = $this->geometry($cropArea, $offset);

				if($blur) {
					exec($this->convert . ' ' . $sourceUrl . ' -crop ' . $geometry . ' +repage -resize ' . $newResolution[0] . 'x' . $newResolution[1] . '! -strip -blur 0x' . $blur . ' -quality '. $quality .' ' . $targetUrl, $o);
				} else {
//					exec($this->convert . ' ' . $sourceUrl . ' -crop ' . $geometry . ' +repage -resize ' . $newResolution[0] . 'x' . $newResolution[1] . '! -strip -quality '. $quality .' ' . $targetUrl, $o);
					$this->cropExec($newResolution[0], $newResolution[1], $sourceUrl, $targetUrl, $geometry, $quality);
				}

				break;

			case 'scale':

				if(!$height) {
					$height = $width;
				}

				$ratio = self::ratio($resolution, $width, $height);
				$newResolution = array(round($resolution[0] * $ratio), round($resolution[1] * $ratio));

//				exec($this->convert . ' ' . $sourceUrl . ' -resize ' . $newResolution[0] . 'x' . $newResolution[1] . '! -strip -quality '. $quality .' ' . $targetUrl);
				$this->resizeExec($newResolution[0], $newResolution[1], $sourceUrl, $targetUrl, $quality);

				break;

			case 'resize':

				if(!$height) {
					$height = $width;
				}

				$newResolution = array($width, $height);

				$this->resizeExec($newResolution[0], $newResolution[1], $sourceUrl, $targetUrl, $quality);
//				exec($this->convert . ' ' . $sourceUrl . ' -resize ' . $newResolution[0] . 'x' . $newResolution[1] . '! -strip -quality '. $quality .' ' . $targetUrl);

				break;

			case 'crop_by_area':

				if(!$height) {
					$height = $width;
				}

				$x = $cropArea['x'];
				$x2 = $cropArea['x2'];
				$y = $cropArea['y'];
				$y2 = $cropArea['y2'];
				$w = $x2 - $x;
				$h = $y2 - $y;

				$geometry = $this->geometry(array($w, $h), array($x, $y));

				$this->cropExec($width, $height, $sourceUrl, $targetUrl, $geometry, $quality);

				break;
		}

		if($greyScale) {
			$this->generateGreyScale($targetUrl);
		}
	}

	/**
	 * @param $width
	 * @param $height
	 * @param $sourceUrl
	 * @param $targetUrl
	 * @param $geometry
	 * @param $quality
	 */
	private function cropExec($width, $height, $sourceUrl, $targetUrl, $geometry, $quality)
	{
		exec($this->convert . ' -background white -alpha remove ' . $sourceUrl  . ' -crop ' . $geometry . ' +repage -resize ' . $width . 'x' . $height . ' -strip -quality '. $quality .' ' . $targetUrl);
	}

	/**
	 * @param $width
	 * @param $height
	 * @param $sourceUrl
	 * @param $targetUrl
	 * @param $quality
	 */
	private function resizeExec($width, $height, $sourceUrl, $targetUrl, $quality)
	{
		exec($this->convert . ' -background white -alpha remove ' . $sourceUrl . ' -resize ' . $width . 'x' . $height . '! -strip -quality '. $quality .' ' . $targetUrl);
	}

	/**
	 * @param $sourceUrl
	 */
	private function generateGreyScale($sourceUrl)
	{
		$destination = substr_replace($sourceUrl, FileRepository::GREY_SCALE_PREFIX, strrpos($sourceUrl, '.'), 0);

		exec($this->convert . ' ' . $sourceUrl  . ' -colorspace gray ' . $destination);
	}

	/**
	 * Calcultae ration
	 *
	 * @param $resolution
	 * @param null $width
	 * @param null $height
	 * @param string $type
	 * @return float|int|mixed
	 */
	private function ratio($resolution, $width = null, $height = null, $type = 'scale')
	{
		$ratio = 1;
		if ($width && $height) {
			if ($type === 'scale') {
				$ratio = min(($width / $resolution[0]), ($height / $resolution[1]));
			} else {
				$ratio = max(($width / $resolution[0]), ($height / $resolution[1]));
			}
		} elseif ($width) {
			$ratio = $width / $resolution[0];
		} elseif ($height) {
			$ratio = $height / $resolution[1];
		}

		return $ratio;
	}

	/**
	 * Calculate geometry
	 * @param $cropArea
	 * @param $offset
	 * @return string
	 */
	private function geometry($cropArea, $offset)
	{
		return ceil($cropArea[0]) . 'x' . ceil($cropArea[1]) . '+' . ceil($offset[0]) . '+' . ceil($offset[1]);
	}

	/**
	 * Calculate offset
	 *
	 * @param $center
	 * @param $resolution
	 * @param $newResolution
	 * @return array
	 */
	private function offset($center, $resolution, $newResolution)
	{
		$offset = [];
		for ($cnt = 0; $cnt <=1; $cnt++) {
			$offset[$cnt] = round($center[$cnt] - ($newResolution[$cnt] / 2));
			if (($offset[$cnt] + $newResolution[$cnt]) > $resolution[$cnt]) {
				$offset[$cnt] = $resolution[$cnt] - $newResolution[$cnt];
			}
			if ($offset[$cnt] < 0) {
				$offset[$cnt] = 0;
			}
		}
		return $offset;
	}
}
