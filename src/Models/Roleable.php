<?php


namespace Webkid\Cms\Models;

/**
 * Trait Roleable
 *
 * @package Webkid\Cms\Traits
 */
trait Roleable
{
	/**
	 * Get the user that owns the role.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function role()
	{
		return $this->belongsTo(UserRole::class);
	}

	/**
	 * Check the user has role admin
	 *
	 * @return bool
	 */
	public function isAdmin()
	{
		return $this->role->name === 'admin' ?: false;
	}

	/**
	 * Check the user has role member
	 *
	 * @return bool
	 */
	public function isMember()
	{
		return $this->role->name === 'member' ?: false;
	}
}
