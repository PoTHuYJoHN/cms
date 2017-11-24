Get started
-----------

```bash
composer require webkid/cms
```

Implement `Webkid\Cms\Models\Roleable` trait to User model

Copy assets to your public folder
```bash
php artisan vendor:publish
```

Run migrations
```bash
php artisan migrate
```

Add role_id to fillable array of User model
```php
protected $fillable = [
	'name',
	'email',
	'password',
	'role_id'
];
```

Go to public/vendor/cms folder and run
```bash
bower install
```

Optional
--------
Generate admin user via console
```bash
php artisan cms:admin:create
```

To use multi lang add Language middleware to web group
```php
'web' => [
        ...
	\Webkid\Cms\Middleware\Language::class
],
```
Add exception cookie `lang` to `App\Http\Middleware\EncryptCookies\`
```php
protected $except = [
    'lang'
];
```

Add language switcher to your website.
Be sure that languages are in your `config/langs.php`
```html
<ul>
	<li><a href="/setLang/ua">ua</a></li>
	<li><a href="/setLang/ru">ru</a></li>
	<li><a href="/setLang/en">en</a></li>
</ul>
```


Example of controller
-------
```php
public function home()
{
	$page = $this->landingPageRepository->getByToken('home');
	if(!$page) abort(404);

	$fields = $this->landingPageRepository->transformPage($page);
	$files = $page->files;


	$seo_title = $this->getSeoAttributeFromFields($fields, 'seo_title');
	$seo_description = $this->getSeoAttributeFromFields($fields, 'seo_description');


	$sliderCollection = $this->landingPageRepository->getByTokenAndSection('home', 'home_slider');
	$sliderItems = $this->landingPageRepository->getFullInfoFromCollectionOfPages($sliderCollection->toArray());

	$settings = $this->settings;

	$fields['video_id'] = false;

	if(isset($fields['video_url'])) {
		$fields['video_id'] = $this->get_youtube_video_id($fields['video_url']);
	}

	return view('pages/home', compact('fields', 'files', 'sliderItems') + compact('seo_title', 'seo_description', 'settings'));
}
```

Example of dashboard extension
------------------------------
```php
<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\File;
use App\Http\Controllers\ControllerTraits\ApiTrait;
use App\__SAMPLE__Recipe as Recipe;
use App\Repositories\__SAMPLE__RecipeRepository as RecipeRepository;
use App\Services\Transformer\__SAMPLE__RecipeTransformer as RecipeTransformer;
use App\Traits\FileSaver;
use App\Traits\Paginatable;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * Class RecipesController
 *
 * @package App\Http\Controllers\Api\Dashboard
 */
class __SAMPLE__RecipesController extends Controller
{
	use ApiTrait, FileSaver, Paginatable;
	/**
	 * @var RecipeRepository
	 */
	private $recipeRepository;
	/**
	 * @var RecipeTransformer
	 */
	private $recipeTransformer;

	/**
	 * RecipesController constructor.
	 *
	 * @param RecipeRepository  $recipeRepository
	 * @param RecipeTransformer $recipeTransformer
	 */
	public function __construct(RecipeRepository $recipeRepository, RecipeTransformer $recipeTransformer)
	{
		$this->recipeRepository = $recipeRepository;
		$this->recipeTransformer = $recipeTransformer;
	}


	/**
	 * @param $id
	 * @return array
	 */
	public function category($id)
	{
		$filters = [
			'category_id' => $id
		];

		$items = $this->recipeRepository->getList($filters, $this->getPaginationLimit('dashboard.recipes'));
		$data =  $this->recipeTransformer->transformCollection($items->toArray()['data']);

		$response = [
				'pagination' => $this->buildPagination($items),
				'list'       => $data
		];

		return $this->respond($response);
	}

	/**
	 * @param Requests\StoreRecipe $request
	 */
	public function store(Requests\StoreRecipe $request)
	{
		$data = $request->all();

		$recipe = Recipe::create($data);

		//add parent_id to files
		$this->createMediaByUser($data, $recipe->id);
	}

	/**
	 * @param                      $id
	 * @param Requests\StoreRecipe $request
	 */
	public function update($id, Requests\StoreRecipe $request)
	{
		$data = $request->all();

		$item = Recipe::findOrFail($id);

		$item->fill($data);

		$item->save();

		$parentCoverToken = false;

		if($item->avatar) {
			$parentCoverToken = $item->avatar['token'];
		}

		//add parent_id to files
		$this->updateMediaByUser($data, $id, $parentCoverToken);
	}

	/**
	 * @param $id
	 * @return mixed|\Symfony\Component\HttpFoundation\Response
	 */
	public function edit($id)
	{
		$item = Recipe::with('avatar','gallery')->findOrFail($id);

		if($item) {
			return $this->respond([
				'item' => $this->recipeTransformer->transform($item->toArray())
			]);
		} else {
			return $this->respondNotFound();
		}
	}

	public function delete($id)
	{
		$item = Recipe::findOrFail($id);

		$item->delete();

		//delete cover
		if($item->avatar && $item->avatar['token']) {
			File::findOrFail($item->avatar['id'])->update(['parent_id' => 0]);
		}

		//delete images
	}
}

```

Example of repo
-
```php
<?php


namespace App\Repositories;
use App\Recipe;


/**
 * Class LandingPageFieldRepository
 *
 * @package App\Repositories
 */
class __SAMPLE__RecipeRepository
{
	/**
	 * @param array $filters
	 * @param       $limit
	 * @return mixed
	 */
	public function getList($filters = [], $limit)
	{
		$query = Recipe::with('avatar');

		$items = $this->applyFilters($query, $filters)->paginate($limit);

		return $items;
	}

	/**
	 * @param int $limit
	 * @return mixed
	 */
	public function getLast($limit = 6)
	{
		return Recipe::with('avatar')->orderBy('recipes.id', 'DESC')->take($limit)->get();
	}

	/**
	 * @param       $query
	 * @param array $filters
	 * @return mixed
	 */
	private function applyFilters($query, array $filters = [])
	{
		//searching by first name or last name or combined
		if (array_key_exists('category_id', $filters)) {
			$query->where('category_id', $filters['category_id']);
		}

		//ordering
		$query->orderBy('recipes.id', 'DESC');


		return $query;
	}
}

```
