<?php

namespace Webkid\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPageFieldsText extends Model {

	/**
	 * @var string
	 */
	protected $table = 'landing_page_fields_text';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = [
		'field_id',
		'lang',
		'value'
	];

}
