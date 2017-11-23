<?php

namespace Webkid\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model {

	/**
	 * @var string
	 */
	protected $table = 'files';

	/**
	 * @var array
	 */
	protected $fillable = [
		'type',
		'parent_id',
		'user_id',
		'token',
		'name',
		'ext',
		'isImage',
		'size'
	];

}
