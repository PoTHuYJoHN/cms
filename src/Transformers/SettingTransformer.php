<?php


namespace Webkid\Cms\Transformers;


/**
 * Class FaqTransformer
 *
 * @package App\Transformers
 */
class SettingTransformer extends Transformer {

	/**
	 * @param $setting
	 *
	 * @return array
	 *
	 */
	public function transform($setting)
	{
		return [
			'id' => (int)$setting['id'],
			'key' => $setting['key'],
			'value' => $setting['value'],
			'label' => $setting['label'],
			'position' => $setting['position'],
			'type' => $setting['type']
		];
	}
}
