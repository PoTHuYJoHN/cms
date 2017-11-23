<?php


namespace Webkid\Cms\Repositories;
use Webkid\Cms\Setting;


/**
 * Class FileRepository
 *
 * @package App\Repositories
 */
class LandingPageItemRepository
{
	/**
	 * @param $items
	 *
	 * @return array
	 */
	public static function prepareWithToken($items)
	{
		$data = [];

		foreach($items as $item) {
			$data[$item->token] = $item->toArray();
		}
		return $data;
	}
}
