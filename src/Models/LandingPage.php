<?php

namespace Webkid\Cms\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LandingPage
 *
 * @package Webkid\Cms\Models
 */
class LandingPage extends Model {

	use Sluggable;

	/**
	 * @var string
	 */
	protected $table = 'landing_pages';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = [
		'token',
		'parent_id',
		'section',
		'slug',
		'coverToken'
	];


	/**
	 * Get fields for the page
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function fields()
	{
		return $this->hasMany(LandingPageField::class, 'page_id');
	}

	/**
	 * Get files for the page
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function files()
	{
		return $this->hasMany(File::class, 'parent_id');
	}

	/**
	 * Return the sluggable configuration array for this model.
	 *
	 * @return array
	 */
	public function sluggable(): array
	{
		return [
			'slug' => [
				'source' => ''
			]
		];
	}
}
