<?php


namespace Webkid\Cms\Repositories\Traits;

/**
 * Class FileSaver
 *
 * @package App\Repositories\Traits
 */
trait TokenGenerator
{
	/**
	 * Generate unique token for any model
	 *
	 * @param        $model
	 * @param int    $length
	 * @param string $field
	 * @return string
	 * @throws \Exception
	 */
	public function generateToken($model, $length = 32, $field = 'token')
	{
		$cnt = 0;

		while (true === $model::where($field, $token = mb_strtolower(str_random($length)))->exists() && $cnt < 10) {
			$cnt++;
		}

		if ($cnt == 10) {
			throw new \Exception('Can not create token.');
		}

		return $token;
	}
}
