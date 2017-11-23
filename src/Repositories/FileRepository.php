<?php


namespace Webkid\Cms\Repositories;


use App\User;
use Webkid\Cms\Models\File;
use Webkid\Cms\Repositories\Traits\TokenGenerator;
use Webkid\Cms\Services\Image;
use Carbon\Carbon;
use Config;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Storage;

/**
 * Class FileRepository
 *
 * @package App\Repositories
 */
class FileRepository
{
	use TokenGenerator;

	const GREY_SCALE_PREFIX = '_greyscale';

	/**
	 * @param            $parentId
	 * @param            $type
	 * @param bool|false $single
	 * @return mixed
	 */
	public static function getByParentId($parentId, $type, $single = false)
	{
//		$query = File::whereRaw('parent_id = ? AND type = ?', [$parentId, $type])
//			->orderBy('created_at', 'desc');

		$method = is_array($parentId) ? 'whereIn' : 'where';
		$typeMethod = is_array($type) ? 'whereIn' : 'where';
		$query = File::$method('parent_id', $parentId)
			->$typeMethod('type', $type)
			->orderBy('date', 'desc');

//		$query->where('type', $type)
//			->orderBy('created_at', 'desc');

		return $single
			? $query->first()
			: $query->get()->toArray();
	}

	/**
	 * @param $token
	 *
	 * @return mixed
	 */
	public function getByToken($token)
	{
		$file = File::where('token', $token)->first();

		if($file) {
			 return $file;
		}

		throw new ModelNotFoundException;
	}

	/**
	 * @param int $limit
	 * @return mixed
	 */
	public function getFilesToBeDeleted($limit = 200)
	{
		return File::whereRaw('(parent_id IS NULL OR parent_id = 0)')

			->where('date', '<', Carbon::now()->subHour(3)->toDateTimeString())
			->orderBy('date', 'DESC')
			->take($limit)
			->get()
			;
	}

	/**
	 * Delete file from storage and from DB
	 * @param File $file
	 * @throws \Exception
	 */
	public function deleteFile(File $file)
	{
		$fileFolder = FileRepository::generatePath($file->type, $file->token);

		if($fileFolder){
			\Log::info('Folder : ' . $fileFolder . ' was removed');
			\Storage::deleteDirectory($fileFolder);
		}

		$file->delete();
		\Log::info('File #' . $file->token . ' was removed from DB');
	}

	/**
	 * @param $user_id
	 * @return mixed
	 */
	public function getUsedSpace($user_id)
	{
		return File::where('sender_id', $user_id)->whereRaw('(parent_id IS NOT NULL AND parent_id != 0)')->sum('size');
	}

	/**
	 * @param       $parentId
	 * @param       $type
	 * @param array $files
	 */
	public static function updateFilesParentId($parentId, $type, array $files)
	{
		// Check if array (fix, if size > allowed = js write string)
		if(!is_array($files)) return;

		//first set 0 for old items
		File::whereRaw('parent_id = ? AND type = ?', [$parentId, $type])
			->update(['parent_id' => 0]);

		//update new items
		foreach($files as $file) {
			if(!$file || !is_array($file) || !isset($file['token'])) continue; // Skip if not file array
			$imageFile = File::where('token', '=', $file['token'])->first();
			$imageFile->update(['parent_id' => $parentId]);
			$imageFile->update(['parent_id' => $parentId]);
		}
	}

	/**
	 * @param $token
	 * @param $parentId
	 */
	public static function setParentIdByToken($token, $parentId)
	{
		if(is_array($token)) {
			$query = File::whereIn('token', $token);
		} else {
			$query = File::whereToken($token);
		}
		$query->update(['parent_id' => $parentId]);
	}

	/**
	 * @param       $parentId
	 * @param array $files
	 */
	public static function setParentIdByIdForUser($parentId, array $files)
	{
		// Check if array (fix, if size > allowed = js write string)
		if(!is_array($files)) return;

		foreach($files as $file) {

			if(!$file || !is_array($file) || !isset($file['id'])) continue; // Skip if not file array

			// If token removed, set parent_id to 0;
			if(!isset($file['token'])) {
				File::where('user_id', \Auth::id())->where('id', '=', $file['id'])->update(['parent_id' => 0]);
			} else {
				File::where('user_id', \Auth::id())->where('id', '=', $file['id'])->update(['parent_id' => $parentId]);
			}
		}
	}

	/**
	 * @param $items
	 *
	 * @return mixed
	 */
	public static function transformWithDimensionUrls($items)
	{
		$result = [];

		foreach($items as $item) {
			$sizes = self::getSizes($item['type']);
			foreach($sizes as $key => $size) {
				$item['url_' . $key] = self::generateUrl($item['type'], $item['token'], $key);
			}

			$result[] = $item;
		}

		return $result;
	}


	/**
	 * Get all prepared sizes. For Angular cfg etc.
	 * @return array
	 */
	public static function getAllSizes()
	{
		$result = [];

		foreach(config('files.sizes') as $type => $sizes) {
			$result[$type] = self::getSizes($type);
		}

		return $result;
	}

	/**
	 * @param      $fileType
	 * @param bool $thumb
	 *
	 * @return array
	 */
	public static function getSizes($fileType, $thumb = false) {
		$config = config('files.sizes');

		$defaultSizes = $config[FILE_DEFAULT];
		$sizes = $config[$fileType];

		if($thumb) {
			return array_merge($defaultSizes[$thumb], $sizes[$thumb]);
		}

		$result = [];
		foreach ($sizes as $thumb => $size) {
			$result[$thumb] = array_merge($defaultSizes[$thumb], $size);
		}

		return $result;
	}

	/**
	 * @param      $type
	 * @param      $token
	 * @param      $isImage
	 * @param bool $create
	 *
	 * @return string
	 */
	public static function generatePath($type, $token, $create = false)
	{
		$config = config('files');

		if(! isset($config['dir'][$type])) {
			return false;
		}

		$dir = $config['assets_dir'] . '/' . $config['dir'][$type];
		//$dir .= DIRECTORY_SEPARATOR . ($isImage ? 'image' : 'files');
		for($i = 0; $i <=2; $i++) {
			$dir .= DIRECTORY_SEPARATOR . $token[$i];
		}
		$dir .= DIRECTORY_SEPARATOR;

//		if($isImage) {
			$dir .= $token . DIRECTORY_SEPARATOR;
//		}

		if(! is_dir($dir) && $create) {
			Storage::makeDirectory($dir);
		}

		return $dir;
	}

	/**
	 * Helper method to get path by App/File
	 * @param File $file
	 *
	 * @return string
	 */
	public function generateFilePath(File $file)
	{
		return FileRepository::generatePath($file->type, $file->token);
	}

	/**
	 * @param File $file
	 *
	 * @return string
	 */
	public function generateDownloadLink(File $file)
	{
		return public_path() . DIRECTORY_SEPARATOR . $this->generateFilePath($file) . config('files.original_filename') . '.' . $file->ext;
	}

	/**
	 * @param $file_ext
	 * @return string
	 */
	public static function generateFileName($file_ext)
	{
		return Config::get('files.original_filename') . '.' . $file_ext;
	}

	/**
	 * @param File   $file
	 * @param        $size
	 * @param string $ext
	 *
	 * @return string
	 */
	public static function getUrl(File $file, $size, $ext = 'jpg')
	{
		return self::generateUrl($file->type, $file->token, $size, $ext);
	}

	/**
	 * @param        $type
	 * @param        $token
	 * @param        $size
	 * @param string $ext
	 *
	 * @return string
	 */
	public static function generateUrl($type, $token, $size, $ext = 'jpg')
	{
		if(! $token){
			return self::generateUrlStubs($type, $size);
		}
		$dirPath = self::generatePath($type, $token);

		return DIRECTORY_SEPARATOR . $dirPath . $size . '.' . $ext;
	}

	/**
	 * @param        $type
	 * @param        $token
	 * @param        $size
	 * @param string $ext
	 * @return string
	 */
	public static function generateGreyScaleUrl($type, $token, $size, $ext = 'jpg')
	{
		if(! $token){
			return self::generateUrlStubs($type, $size);
		}
		$dirPath = self::generatePath($type, $token);

		return DIRECTORY_SEPARATOR . $dirPath .  $size . self::GREY_SCALE_PREFIX . '.' . $ext;
	}

	/**
	 * @param        $type
	 * @param        $size
	 *
	 * @return string
	 */
	public static function generateUrlStubs($type, $size)
	{
		$config = Config::get('files');

		return DIRECTORY_SEPARATOR . $config['stubs_dir']  .'/' . $config['dir'][$type] . '/' . $size . '.jpg';
	}

	/**
	 * @param File $file
	 * @param bool $fullPath
	 * @param bool $size
	 * @return string
	 */
	public function getRealUrl(File $file, $fullPath = true, $size = false)
	{
		if( ! $file->isImage ) {
			$path = FileRepository::generatePath($file->type, $file->token);
			$url = $path . FileRepository::generateFileName($file->ext);
		} else {
			//todo check for ext if correct
			if($size) {
				$sizeInfo = FileRepository::getSizes($file->type, $size);
			}
			$url = FileRepository::getUrl($file,
				$size ?: Config::get('files.original_filename'),
				$size ? $sizeInfo['format'] : $file->ext);
		}

		//todo check if DIRECTORY_SEPARATOR is needed

		return ($fullPath ? public_path() : '') .   $url;
	}

	/**
	 * @param            $type
	 * @param            $token
	 * @param bool|false $size
	 * @param string     $ext
	 * @param bool|true  $fullPath
	 * @return string
	 */
//	public static function generateRealUrl($type, $token, $size = false, $ext = 'jpg', $fullPath = true)
//	{
//		if($size) {
//			$sizeInfo = FileRepository::getSizes($type, $size);
//		}
//
//		$url = FileRepository::generateUrl($type, $token,
//			$size ?: Config::get('files.original_filename'),
//			$size ? $sizeInfo['format'] : $ext);
//
//
//		return ($fullPath ? public_path() : '') .   $url;
//	}

	/**
	 * @param            $type
	 * @param            $token
	 * @param bool|false $size
	 * @param string     $ext
	 * @param bool|true  $fullPath
	 * @return string
	 */
	public static function generateRealUrl($type, $token, $size = false, $ext = 'jpg', $fullPath = true)
	{
		if($size) {
			$sizeInfo = FileRepository::getSizes($type, $size);
		}

		$url = FileRepository::generateUrl($type, $token,
			$size ?: Config::get('files.original_filename'),
			$size ? $sizeInfo['format'] : $ext);


		return ($fullPath ? public_path() : '') .   $url;
	}

	/**
	 * @param File $file
	 * @return string
	 */
	public function getRealDir(File $file) {
		return realpath(dirname($this->getRealUrl($file)));
	}


	/**
	 * Create copy file by token and save use other type
	 *
	 * @param      $token
	 * @param      $toType
	 * @param null $parent_id
	 *
	 * @return bool
	 */
	public function copyByToken($token, $toType, $parent_id = null)
	{
		// Get file from Database.
		$file = File::where('token', $token)->first();

		if(!$file) {
			return false;
		}

		// Get full File Path.
		$sourceFileUrl = self::getRealUrl($file);

		$newFile = $this->createCopyFromSource($sourceFileUrl, $toType, $parent_id, $file->toArray(), \Auth::id());

		return $newFile;
	}

	/**
	 * @param      $url
	 * @param      $user_id
	 * @param      $toType
	 * @param null $parent_id
	 * @return bool|static
	 */
	public function copyFromUrl($url, $user_id, $toType, $parent_id = null)
	{
		// Generate TMP Source Path, and save in Source from Url.
		$sourceFileUrl = '/tmp/image_'.rand(0,999999999).'jpg';
		file_put_contents($sourceFileUrl, file_get_contents($url));

		$file = [
			'isImage' => true,
			'name' => 'avatar.jpg',
			'ext' =>  pathinfo($sourceFileUrl, PATHINFO_EXTENSION),
			'size' => filesize($sourceFileUrl),
			'cropArea' => '',
		];

		// Copy and generate sizes.
		$newFile = $this->createCopyFromSource($sourceFileUrl, $toType, $parent_id, $file, $user_id);

		// Remove temporary file.
		if(is_file($sourceFileUrl)) {
			unlink($sourceFileUrl);
		}

		return $newFile;
	}


	/**
	 * @param      $sourceFileUrl
	 * @param      $toType
	 * @param null $parent_id
	 * @param      $file
	 * @param      $sender_id
	 * @return bool|static
	 * @throws \Exception
	 */
	private function createCopyFromSource($sourceFileUrl, $toType, $parent_id = null, $file, $sender_id)
	{
		// Get user. Can be not logged user.
		$user = User::find($sender_id);

		if(empty($user)) {
			return false;
		}

		// Generate new token.
		$resultToken = $this->generateToken('App\File', 8);

		// Generate destination File Url with creating folders.
		$destination = FileRepository::generatePath($toType, $resultToken, true);
		$filename = FileRepository::generateFileName($file['ext']);
		$destinationFileUrl        = $destination . $filename;

		if(!is_file($sourceFileUrl)) {
			return false;
		}

		if(!is_dir($destination)) {
			mkdir($destination, 0777, true);
		}

		// Copy File.
		$copiyed = copy($sourceFileUrl, $destinationFileUrl);

		// If copy SUCCESS.
		if($copiyed) {

			// AND if it is Image, create Dimensions.
			if ($file['isImage']) {
//				$image = new Image();
				$resizer = new Image\Resizer();
				$resizer->createDimensions($toType, $resultToken, $destinationFileUrl);
//				$image->createDimensions($toType, $resultToken, $destinationFileUrl);
			}
		}

		// Generate new file for Database.
		$data = [
			'sender_type' => $user->getType(),
			'sender_id' => $user->id ?: null,
			'type' => $toType,
			'name' => $file['name'],
			'token' => $resultToken,
			'size' => $file['size'],
			'ext' => $file['ext'],
			'isImage' => $file['isImage'],
			'cropArea' => $file['cropArea']
		];
		if($parent_id) {
			$data['parent_id'] = $parent_id;
		}


		// Create new file.
		$newFile = File::create($data);
		return $newFile;
	}

	/**
	 * @param $avaToken
	 * @param $owner
	 * @param $parent_id
	 * @return bool|void
	 */
	public function makeCopyAva($avaToken, $owner, $parent_id)
	{
		if(!$avaToken) return;

		$file = $this->getByToken($avaToken);
		$token = substr($avaToken, 0, 3) . strtolower(str_random(5));

		$src_img = $this->getRealDir($file);
		$dst_img = str_replace($avaToken, $token, $this->getRealDir($file));
		if($src_img && is_dir($src_img)) {
			$command = 'cp -a ' . $src_img . ' ' .$dst_img;
			shell_exec(escapeshellcmd($command));
		}

		$src_file = str_replace('/images/', '/files/', $this->getRealDir($file));
		$dst_file = str_replace('/images/', '/files/', str_replace($avaToken, $token, $this->getRealDir($file)));
		if($src_file && is_dir($src_file)) {
			$command = 'cp -a ' . $src_file . ' ' .$dst_file;
			shell_exec(escapeshellcmd($command));
		}

		$new_file = $file->replicate(['id', 'sender_type', 'sender_id', 'parent_id', 'landLordId', 'token']);

		$new_file->sender_type = $owner->type();
		$new_file->sender_id = $owner->id;
		$new_file->parent_id = $parent_id;
		$new_file->token = $token;
		$new_file->save();

		return true;
	}

	/**
	 * @param $files
	 * @param $owner
	 * @param $parent_id
	 */
	public function makeCopyFiles($files, $owner, $parent_id)
	{
		if($files && count($files)) {

			$test = [];
			$test2 = [];

			if(!$owner || !$parent_id) return;

			foreach($files as $old_file) {
				$token = substr($old_file->token, 0, 3) . strtolower(str_random(5));

				$src_img = $this->getRealDir($old_file);
				$dst_img = str_replace($old_file->token, $token, $this->getRealDir($old_file));

				$test2[] = $src_img;

				if($src_img && is_dir($src_img)) {
					$command = 'cp -a ' . $src_img . ' ' .$dst_img;
					shell_exec(escapeshellcmd($command));

					$test[] = $command;
				}

				if($old_file->isImage) {
					$src_file = str_replace('/files/', '/image/', $this->getRealDir($old_file));
					$dst_file = str_replace('/files/', '/image/', str_replace($old_file->token, $token, $this->getRealDir($old_file)));
					if($src_file && is_dir($src_file)) {
						$command = 'cp -a ' . $src_file . ' ' .$dst_file;
						shell_exec(escapeshellcmd($command));

						$test[] = $command;
					}
				}



				$new_file = $old_file->replicate(['id', 'sender_type', 'sender_id', 'parent_id', 'token']);
				$new_file->sender_type = $owner->type();
				$new_file->sender_id = $owner->id;
				$new_file->parent_id = $parent_id;
				$new_file->token = $token;
				$new_file->save();


			}

			//dd($test, $test2, $new_file);

		}
	}

	/**
	 * @param bool|false $type
	 * @return mixed
	 */
//	public function getActiveImages($type = false, $fields = false)
//	{
//		$query =  File::whereNotNull('parent_id')
//			->where('parent_id', '<>', '0')
//			->where('isImage', '1')
//			;
//
//		if($fields) {
//			$query->select($fields);
//		}
//
//		if($type) {
//			$query->where('type', $type);
//		}
//
//		return $query->get();
//	}


	/**
	 * @param File $file
	 * @param      $size
	 */
//	public function regenerateFileThumbs(File $file, $size)
//	{
//		$fileRealUrl =$this->getRealUrl($file, true);
//		if(file_exists($fileRealUrl)) {
//			$imageService = new Image();
//
//			$imageService->createDimensions($file->type, $file->token, $fileRealUrl, $size);
//		}
//	}

	/**
	 * @param bool|false $type
	 * @return mixed
	 */
//	public function getActiveImages($type = false, $fields = false)
//	{
//		$query =  File::whereNotNull('parent_id')
//			->where('parent_id', '<>', '0')
//			->where('isImage', '1')
//			;
//
//		if($fields) {
//			$query->select($fields);
//		}
//
//		if($type) {
//			$query->where('type', $type);
//		}
//
//		return $query->get();
//	}


	/**
	 * @param File $file
	 * @param      $size
	 */
//	public function regenerateFileThumbs(File $file, $size)
//	{
//		$fileRealUrl =$this->getRealUrl($file, true);
//		if(file_exists($fileRealUrl)) {
//			$imageService = new Image();
//
//			$imageService->createDimensions($file->type, $file->token, $fileRealUrl, $size);
//		}
//	}

	/**
	 * @param bool|false $type
	 * @return mixed
	 */
	public function getActiveImages($type = false, $fields = false)
	{
		$query =  File::whereNotNull('parent_id')
			->where('parent_id', '<>', '0')
			->where('isImage', '1')
			->orderBy('created_at', 'DESC')
			;

		if($fields) {
			$query->select($fields);
		}

		if($type) {
			$query->where('type', $type);
		}

		return $query->get();
	}


	/**
	 * @param File $file
	 * @param      $size
	 */
	public function regenerateFileThumbs(File $file, $size)
	{
		$fileRealUrl = $this->getRealUrl($file, true);
		if(file_exists($fileRealUrl)) {
//			$imageService = new Image();

			$resizer = new Image\Resizer();

			if($file->cropArea && count($file->cropArea) && isset($file->cropArea[$size])) {
				$resizer->createDimensions($file->type, $file->token, $fileRealUrl, $size, $file->cropArea);
			} else {
				$resizer->createDimensions($file->type, $file->token, $fileRealUrl, $size);
			}

			$imageService = null;
			unset($imageService);

			return;
		}
	}
}
