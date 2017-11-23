<?php
namespace Webkid\Cms\Traits;

use Config;
use Input;

/**
 * Class Paginatable
 *
 * @package App\Traits
 */
trait Paginatable
{
	/**
	 * @param $configKey
	 * @return mixed
	 */
	public function getPaginationLimit($configKey = '')
	{
		$fromRequest = Input::get('take');

		if($fromRequest) { //get limit from ?take - parameter
			$max = Config::get('pagination.defaults.max');
			if($fromRequest <= $max) { //if is is bigger then maximum then sent only max
				return $fromRequest;
			} else {
				return $max;
			}

		} else { //get limit from config
			if(!empty(Config::get('pagination.' . $configKey))) {
				return Config::get('pagination.' . $configKey);
			} else { //if not set in config then set default value from global config
				return Config::get('pagination.defaults.average');
			}
		}

	}
}
