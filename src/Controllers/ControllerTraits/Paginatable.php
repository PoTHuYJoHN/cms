<?php

namespace Webkid\Cms\Controllers\ControllerTraits;

/**
 * Class Paginatable
 *
 * @package App\Traits
 */
trait Paginatable
{
	/**
	 * @param string $key
	 * @return mixed
	 */
	public function getPaginationLimit(string $key = '')
	{
		// Get limit from ?take - parameter
		$fromRequest = request('take');
		if ($fromRequest) {
			$max = config('pagination.defaults.max');
			return ($fromRequest <= $max) ? $fromRequest : $max;
		}

		$limit = config("pagination.$key");
		if (!$limit) {
			return config('pagination.defaults.average');
		}

		return $limit;
	}
}
