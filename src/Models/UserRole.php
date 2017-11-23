<?php

namespace Webkid\Cms\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserRole
 *
 * @package App
 */
class UserRole extends Model {

	/**
	 * @var string
	 */
	protected $table = 'user_roles';

	/**
	 * @var array
	 */
	protected $fillable = [
		'name'
	];

}
