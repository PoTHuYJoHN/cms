<?php


namespace Webkid\Cms\Traits;

use Webkid\Cms\Repositories\FileRepository;

/**
 * Class FileSaver
 *
 * @package App\Repositories\Traits
 */
trait FileSaver
{
	/**
	 * Create files
	 * @param array $data
	 * @param       $parentId
	 */
	public function createMediaByUser(array $data, $parentId)
	{
		// Set images parent id
		if(!empty($data['coverToken'])) {
			FileRepository::setParentIdByToken($data['coverToken'], $parentId);
		}

		// Check files
		if(isset($data['files']) && !empty($data['files'])) {
			FileRepository::setParentIdByIdForUser($parentId, $data['files']);
		}

		// Check gallery
		if(isset($data['gallery']) && !empty($data['gallery'])) {
			FileRepository::setParentIdByIdForUser($parentId, $data['gallery']);
		}
	}

	/**
	 * Update files. Remove old files and add new.
	 * Update avatar file with parent_id
	 *
	 * @param array $data
	 * @param       $parentId
	 * @param bool  $parentCoverToken
	 */
	public function updateMediaByUser(array $data, $parentId, $parentCoverToken = false)
	{
		//persist cover image
		// if change
		if(!empty($data['coverToken']) && isset($parentCoverToken)) {
			FileRepository::setParentIdByToken($parentCoverToken, null);
			FileRepository::setParentIdByToken($data['coverToken'], $parentId);
		}

		//add parent_id if was not created yet
		if(!empty($data['coverToken']) && !$parentCoverToken) {
			FileRepository::setParentIdByToken($data['coverToken'], $parentId);
		}
		// if remove
		if(empty($data['coverToken']) && isset($parentCoverToken)) {
			FileRepository::setParentIdByToken($parentCoverToken, null);
		}

		// Check files
		if(isset($data['files']) && !empty($data['files'])) {
			FileRepository::setParentIdByIdForUser($parentId, $data['files']);
		}

		// Check gallery
		if(isset($data['gallery']) &&!empty($data['gallery'])) {
			FileRepository::setParentIdByIdForUser($parentId, $data['gallery']);
		}
	}

}
