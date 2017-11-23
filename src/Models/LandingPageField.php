<?php

namespace Webkid\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPageField extends Model {

	/**
	 * @var string
	 */
	protected $table = 'landing_page_fields';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = [
		'page_id',
		'key',
		'type',
		'label',
		'editor',
		'position'
	];

	/**
	 * Get texts for the field
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function texts()
	{
		return $this->hasMany(LandingPageFieldsText::class, 'field_id');
	}

}
