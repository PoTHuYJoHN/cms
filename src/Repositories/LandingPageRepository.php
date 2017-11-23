<?php


namespace Webkid\Cms\Repositories;

use Webkid\Cms\Models\LandingPage;


/**
 * Class FileRepository
 *
 * @package App\Repositories
 */
class LandingPageRepository
{

	/**
	 * @param      $token
	 *
	 * @param bool $lang
	 *
	 * @return mixed
	 */
	public function getByToken($token, $lang = false)
	{
		$gallery = config('pages.' . $token . '.gallery') ?: false;



		$query =  LandingPage::where('token', $token)
				->with(['fields' => function($query) use($lang){
					$query->with(['texts' => function($query) use($lang) {
						if($lang) {
							$query->where('lang', $lang);
						}
					}]);
				}]);

		if($gallery) {
			$query->with(['files' => function($query) use($gallery) {
				$query->where('type', $gallery['fileType']);
			}]);
		}
		return $query->first();
	}

	/**
	 * @param            $slug
	 * @param            $section
	 * @param bool|false $lang
	 * @return mixed
	 */
	public function getBySlug($slug, $section, $lang = false)
	{
		$query =  LandingPage::where('slug', $slug);

		$query = $this->queryHelper($query, $section, $lang);

		return $query->first();
	}

	/**
	 * @param            $token
	 * @param            $section
	 * @param bool       $limit
	 * @param bool|false $lang
	 * @return
	 */
	public function getByTokenAndSection($token, $section, $limit = false, $lang = false)
	{
		$masterPage = LandingPage::where('token', $token)->firstOrFail();

		$query =  LandingPage::where('section', $section)
				->where('parent_id', $masterPage->id)
		;

		if($limit) {
			return $this->queryHelper($query, $section, $lang)->paginate($limit);
		} else {
			return $this->queryHelper($query, $section, $lang)->get();
		}
	}

	/**
	 * @param      $parentId
	 * @param      $section
	 * @param bool $lang
	 * @return mixed
	 */
	public function getByParentId($parentId, $section, $lang = false)
	{
		//todo get files if gallery is in config
		$query = LandingPage::where('parent_id', $parentId);
		return $this->queryHelper($query, $section, $lang)->get();
	}

	/**
	 * @param      $id
	 * @param      $section
	 * @param bool $lang
	 * @return mixed
	 */
	public function getById($id, $lang = false)
	{
		$item = LandingPage::findOrFail($id);

		$query = LandingPage::where('id', $id);
		return $this->queryHelper($query, $item->section, $lang)->first();
	}

	/**
	 * @param $query
	 * @param $lang
	 *
	 * @return mixed
	 */
	private function queryHelper($query, $section, $lang)
	{
		$gallery = config('pages.' . $section . '.gallery') ?: false;

		if($gallery) {
			$query->with(['files' => function($query) use($gallery) {
				$query->where('type', $gallery['fileType']);
			}]);
		}

		$query->with(['fields' => function($query) use($lang){
			$query->with(['texts' => function($query) use($lang) {
				if($lang) {
					$query->where('lang', $lang);
				}
			}]);
		}]);

		return $query;
	}

	/**
	 * @param $page
	 *
	 * @return array
	 */
	public function transformPage($page)
	{
		$data = array();

		foreach($page['fields'] as $field) {
//			if(is_array($field['texts'][0])) {
//				$data[$field['key']] = $field['texts'][0]['value'];
//			} else {
			$data[$field['key']] = $field['texts'][0]->value;
//			}

		}

//		dd($page, $data);

		if($page->coverToken) {
			$data['coverToken'] = $page->coverToken;
		}
		return $data;

	}

	/**
	 * @param array $collection
	 * @return array
	 */
	public function transformCollectionOfPages(array $collection)
	{
		return array_map([$this, 'transformPage'], $collection);
	}

	/**
	 * @param $page
	 * @return mixed
	 */
	public function getFullInfoFromPage($page)
	{
		$fields = $page['fields'];

		$page['fields'] = [];

		foreach($fields as $field) {
			if(is_array($field['texts'][0])) {
				$page['fields'][$field['key']] = $field['texts'][0]['value'];
			}
//			else {
//				$page[$field['key']] = $field['texts'][0]->value;
//			}

		}
		return $page;
	}

	/**
	 * @param array $collection
	 * @return array
	 */
	public function getFullInfoFromCollectionOfPages(array $collection)
	{
		return array_map([$this, 'getFullInfoFromPage'], $collection);
	}

}
