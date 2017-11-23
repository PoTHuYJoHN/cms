<?php

namespace Webkid\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
	/**
	 * @var string
	 */
	protected $table = 'subscribers';

	/**
	 * @var array
	 */
	protected $fillable = [
		'email'
	];
}
