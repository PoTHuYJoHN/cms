<?php


namespace Webkid\Cms\Transformers;


/**
 * Class LandingPageTransformer
 *
 * @package App\Transformers
 */
class LandingPageTransformer extends Transformer {

	/**
	 * @param $item
	 *
	 * @return array|mixed
	 */
	public function transform($item)
	{
		return [
			'id' => (int)$item['id'],
			'token' => $item['token'],
			'text' => unserialize(base64_decode($item['text']))
		];
	}

	/**
	 * @param $item
	 *
	 * @return array
	 */
	public function deTransform($item)
	{
		return [
			'id' => (int)$item['id'],
			'token' => $item['token'],
			'text' => base64_encode(serialize($item['text']))
		];
	}
}
