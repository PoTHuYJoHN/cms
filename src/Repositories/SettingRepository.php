<?php


namespace Webkid\Cms\Repositories;
use Webkid\Cms\Models\Setting;


/**
 * Class FileRepository
 *
 * @package App\Repositories
 */
class SettingRepository
{
	/**
	 * Return key:value pair
	 * @return array
	 */
	public static function prepareAll()
	{
		$data = [];
		$settings = Setting::all();

		foreach($settings as $setting) {
			$data[$setting->key] = $setting->value;
		}
		return $data;
	}

	/**
	 *
	 * @param $data
	 */
	public static function updateAll($data)
	{
		foreach($data as $key => $value) {
			Setting::where('key', $key)->update(['value'=>$value]);
		}
	}
}
