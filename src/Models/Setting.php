<?php

namespace Webkid\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

	/**
	 * @var string
	 */
	protected $table = 'settings';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = [
		'key',
		'label',
		'value',
		'type',
		'isRoot',
		'position'
	];

}
