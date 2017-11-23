<?php


namespace Webkid\Cms\Services;
use Config;
use Webkid\Cms\Repositories\FileRepository;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Log;
use Storage;


/**
 * Class Image
 *
 * @package App\Services
 */
class Image
{
	/**
	 * Upload an image to the public storage
	 *
	 * @param  File $file
	 * @param       $type
	 * @param bool  $token
	 * @param bool  $isImage
	 *
	 * @return string
	 *
	 */
	public function upload($file, $type, $token = false, $isImage = true)
	{
		// Get file info and try to move
		$destination = FileRepository::generatePath($type, $token, true);

		$filename 	 = FileRepository::generateFileName($file->getClientOriginalExtension());
		$path        =  $destination . '/' . $filename;

		$uploaded    = $file->move($destination, $filename);

		if ($uploaded)
		{
			$resizer = new Image\Resizer();

			if ($isImage) $resizer->createDimensions($type, $token, $path);

			return array(
					'filename'=>$filename
			);
		}
	}

	/**
	 * @param $data
	 * @param $type
	 * @param bool|false $token
	 * @return array|null
	 */
	public function uploadGoogleStreetView($data, $type, $token = false)
	{
		// Check params
		if(!$data['lat'] || !$data['lon'] || !$data['heading'] || !$data['pitch'] || !$data['zoom']) {
			return null;
		}

		// Get file info and try to move
		$destination = FileRepository::generatePath($type, $token, true);

		$fov = array(120, 90, 53.5, 28.3, 14.3, 10);
		$fov = (round($data['zoom']) <= 5) ? $fov[round($data['zoom'])] : 90;

		$url = "http://maps.googleapis.com/maps/api/streetview?size=640x640&location=".$data['lat']."%2C".$data['lon'].
				"&heading=".$data['heading']."&pitch=".$data['pitch']."&fov=".$fov."&sensor=false";

		$name = Config::get('files.original_filename').'.jpg';

		//		OLD
//		$input = fopen($url, "r");
//		$temp = tmpfile();
//		$realSize = stream_copy_to_stream($input, $temp);
//		fclose($input);
//		$target = fopen($destination . $name, "w");
//		fseek($temp, 0, SEEK_SET);
//		stream_copy_to_stream($temp, $destination);
//		fclose($target);

		// store image to storage/assets
		$uploaded = \Storage::writeStream($destination . $name, fopen($url, 'r'));

		if ($uploaded)
		{
			$resizer = new Image\Resizer();
			$resizer->createDimensions($type, $token, $destination . $name);

			$fileSize = \Storage::size($destination . $name);

			return array(
					'name' => $name,
					'size' => $fileSize
			);
		} else {
			return null;
		}
	}
}
