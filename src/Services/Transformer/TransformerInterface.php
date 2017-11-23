<?php namespace Webkid\Cms\Services\Transformer;

interface TransformerInterface
{

	/**
	 * Transforms a given array
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function transform(array $data);
}
