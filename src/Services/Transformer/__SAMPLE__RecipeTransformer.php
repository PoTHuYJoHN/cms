<?php
namespace Webkid\Cms\Services\Transformer;

class __SAMPLE__RecipeTransformer extends Transformer
{
	/**
	 * Transforms a given array
	 *
	 * @param array $data
	 * @return array
	 */
	public function transform(array $data)
	{
		$data['coverToken'] = null;

		/**
		 * Ava
		 */
		if (isset($data['avatar']) && $data['avatar']) {
			$data['coverToken'] = $data['avatar']['token'];
			$data['coverExt'] = $data['avatar']['ext'];
			unset($data['avatar']);
		}

		return $data;
	}
}
