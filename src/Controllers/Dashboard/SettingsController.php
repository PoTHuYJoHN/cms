<?php namespace Webkid\Cms\Controllers\Dashboard;

use Webkid\Cms\Controllers\ApiController;
use Webkid\Cms\Models\Setting;
use Webkid\Cms\Repositories\SettingRepository;

use Webkid\Cms\Transformers\SettingTransformer;
use Illuminate\Http\Request;

/**
 * Class SettingController
 *
 * @package Webkid\Cms\Http\Controllers\Api
 */
class SettingsController extends ApiController {

	/**
	 * @var
	 */
	protected $transformer;

	/**
	 * @param $transformer
	 */
	public function __construct(SettingTransformer $transformer)
	{
		$this->transformer = $transformer;
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$items =  Setting::all();

		return $this->respond([
			'data' => $this->transformer->transformCollection($items->toArray())
		]);

	}

	/**
	 * Massive update of All settings
	 *
	 * @param Request $request
	 */
	public function updateAll(Request $request)
	{
		SettingRepository::updateAll($request->all());
	}
}
