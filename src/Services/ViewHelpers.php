<?php


namespace Webkid\Cms\Services;

use Webkid\Cms\Repositories\FileRepository;

class ViewHelpers {

	public static function getBackendCfg()
	{
		$files = \Config::get('files');

		$files['sizes'] = FileRepository::getAllSizes();

		return json_encode([
			'ENV' => app()->environment(),
			'files' => $files,
			'CSRF_TOKEN' => csrf_token(),
			'langs' => \Config::get('langs'),

			'api' => [
//				'stripe'	=> \Config::get('services.stripe.secret')
			],
			'resources' => [
				//SOME LANG ITEMS, FOR SELECT ETC.
//				'trade_in_types' => ['Leased', 'Financed', 'Owned', 'Other'],
//				'trade_in_conditions' => ['Excellent', 'Good', 'Fair', 'Poor']
			]
		]);
	}
}
