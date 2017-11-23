<?php


namespace Webkid\Cms\Repositories;


use Webkid\Cms\User;

class UserRepository
{
	const USER_ROLE_MEMBER = 1;
	const USER_ROLE_ADMIN = 2;

	public function findByEmailOrCreate($userData)
	{
		$user = User::where('email', '=', $userData->email)->first();

		if(!$user) {
			return User::firstOrCreate([
				'name' => $userData->name,
				'email'=> $userData->email,
				'role_id' => self::USER_ROLE_MEMBER
			]);
		}

		return $user;

	}

	public function getList()
	{
		$result = [];

		foreach (User::with('profile')->where('role_id', self::USER_ROLE_MEMBER)->get()->toArray() as $user)
		{
			$result[$user['id']] = $user['profile']['first_name'] . ' ' . $user['profile']['last_name'];
		}

		return $result;
	}
}
