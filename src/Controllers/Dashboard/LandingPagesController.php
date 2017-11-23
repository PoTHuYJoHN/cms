<?php namespace Webkid\Cms\Controllers\Dashboard;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Webkid\Cms\Controllers\ApiController;

use Webkid\Cms\Models\File;
use Webkid\Cms\Models\LandingPage;
use Webkid\Cms\Repositories\FileRepository;
use Webkid\Cms\Repositories\LandingPageFieldRepository;
use Webkid\Cms\Repositories\LandingPageRepository;
use Webkid\Cms\Traits\FileSaver;
use Webkid\Cms\Transformers\LandingPageTransformer;
use Illuminate\Http\Request;

class LandingPagesController extends ApiController
{

	use FileSaver;
	/**
	 * @var
	 */
	protected $transformer;

	public function __construct(LandingPageTransformer $transformer)
	{
		$this->transformer = $transformer;
	}

	public function listByParent($parentId, $section, LandingPageRepository $landingPageRepository)
	{
		$page  = LandingPage::find($parentId);
		$items = $landingPageRepository->getByParentId($parentId, $section);

		$this->additionalShowBySection($page->token, $section, $items);

		$response = [
			'data' => $items
		];

		return $this->respond($response);
	}

	public function create($section)
	{
		$response = [
			'fields' => config('landing.' . $section . '.options')
		];


		return $this->respond($response);
	}


	/**
	 * @param                       $token
	 * @param LandingPageRepository $landingPageRepository
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function show($token, LandingPageRepository $landingPageRepository)
	{
		$page = $landingPageRepository->getByToken($token);

		if (!$page) {
			return $this->respondNotFound('Page does not exists');
		}

		$response = [
			'data' => $page
		];

		$this->additionalShow($token, $response, $page);


		return $this->respond($response);
	}


	/**
	 * @param                       $token
	 * @param LandingPageRepository $landingPageRepository
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editByToken($token, LandingPageRepository $landingPageRepository)
	{
		$page = $landingPageRepository->getByToken($token);

		return $this->editHelper($page);
	}

	public function getByTokenAndSection($token, $section, LandingPageRepository $landingPageRepository)
	{
		$items = $landingPageRepository->getByTokenAndSection($token, $section);

		return $this->respond([
			'list' => $items->toArray()
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param                       $id
	 * @param LandingPageRepository $landingPageRepository
	 * @return Response
	 */
	public function edit($id, LandingPageRepository $landingPageRepository)
	{
		$page = $landingPageRepository->getById($id);

		return $this->editHelper($page);
	}

	/**
	 * Edit master page or page with elements
	 */
//	public function editPage($token, $section = false, LandingPageRepository $landingPageRepository)
//	{
//		if($section) { //get master page element
////			$masterPage = LandingPage::where('token', $token);
//			//todo
//		} else {
//			//master page
//			$page = $landingPageRepository->getByToken($token);
//			return $this->editHelper($page);
//		}
//	}

	private function editHelper($page)
	{
		if (!$page) {
			return $this->respond([]);
//			return $this->respondNotFound('Page does not exists');
		}

//		$images = FileRepository::getByParentId($page->id, File::FILE_ABOUT_LANDING);

		return $this->respond([
			'fields' => $page->fields,
			'item'   => $page
//			'images' => FileRepository::transformWithDimensionUrls($images)
		]);
	}

	public function updateAllLangs(
		$id,
		LandingPageFieldRepository $landingPageFieldRepository
	) {
		$data = request()->all();

		$page = LandingPage::find($id);

		foreach ($data['items'] as $langFields) {
			$landingPageFieldRepository->updateFields($page->id, $langFields['lang'],
				$langFields['fields']);
		}
		$this->updateMediaByUser($data, $id, $page->coverToken);

		$page->coverToken = array_get($data, 'coverToken');
		$page->save();
	}


	public function store(Request $request, LandingPageFieldRepository $landingPageFieldRepository)
	{
		$data = $request->all();

		if (!empty($data['section'])) {
			if (!empty($data['token'])) {
				$masterPage        = LandingPage::where('token', $data['token'])->first();
				$data['parent_id'] = $masterPage['id'];
				unset($data['token']);
			}
		}

		$page = LandingPage::create($data);

		//check if slug is needed via config
		if (!empty($data['section'])) {
			if (array_key_exists('slug', config('pages.' . $data['section']))) {

				$slugField = config('pages.' . $data['section'] . '.slug');

				//generate slug from english version of field
				$title = $data['items'][0]['fields'][$slugField]['value'];

				$page->slug = SlugService::createSlug(LandingPage::class, 'slug', $title ?? str_random(8));
				$page->save();
			}
		}

		$landingPageFieldRepository->createFields($page->id, $data);

		if (!empty($data['coverToken'])) {
			File::whereToken($data['coverToken'])
				->update(['parent_id' => $page->id]);
		}

		$this->createMediaByUser($data, $page->id);
	}

	public function destroy($id)
	{
		LandingPage::destroy($id);
		//todo remove files if needed
	}

	/**
	 * Update page old url
	 *
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function updateOldUrl($id)
	{
		$item = LandingPage::findOrFail($id);

		$value = request('old_url');

		$item->old_url = $value;
		$item->save();

		return $this->respond([]);
	}


	/*
	|--------------------------------------------------------------------------
	| HELPER FUNCTIONS
	|--------------------------------------------------------------------------
	*/

	/**
	 * Helper method for update by page token
	 *
	 * @param       $token
	 * @param       $page
	 * @param array $images
	 */
	private function additionalUpdate($token, $page, $images = [])
	{
		switch ($token) {
			case 'home' :
				//save files parent_id
//				FileRepository::updateFilesParentId($page->id, File::FILE_HOME_PARTNER,  $images);
				break;
		}

	}

	/**
	 * Get additional info about page by token
	 *
	 * @param $token
	 * @param $response
	 * @param $page
	 */
	private function additionalShow($token, &$response, $page)
	{
//		switch ($token) {
//			case 'home' :
//				$images = FileRepository::getByParentId($page->id, File::FILE_HOME_SLIDER);
//				break;
//		}
//
//		if (isset($images)) {
//			$response['images'] = FileRepository::transformWithDimensionUrls($images);
//		}
	}

	/**
	 * @param $parentToken
	 * @param $section
	 * @param $items
	 */
	private function additionalShowBySection($parentToken, $section, &$items)
	{
//		foreach ($items as $item) {
//			if ($parentToken == 'home' && $section == 'slider') {
//				$item->images = FileRepository::getByParentId($item->id, File::FILE_HOME_SLIDER);
//			}
//		}
	}

}
