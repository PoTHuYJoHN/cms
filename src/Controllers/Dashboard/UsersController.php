<?php

namespace Webkid\Cms\Controllers\Dashboard;

use App\User;
use Webkid\Cms\Controllers\ApiController;
use Webkid\Cms\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

/**
 * Class UsersController
 *
 * @package Webkid\Cms\Controllers\Dashboard
 */
class UsersController extends ApiController
{
	use RegistersUsers;
	/**
	 * Display a listing of the users.
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index()
	{
		return $this->respond([
			'users' => User::all()
		]);
	}

	/**
	 * Show the form for creating a new user.
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function create()
	{
		return $this->respond([
			'roles' => UserRole::pluck('name', 'id')
		]);
	}

	/**
	 * Store a newly created user in storage.
	 *
	 * @param Request   $request
	 */
	public function store(Request $request)
	{
		$data = $request->all();

		Validator::make($data, [
			'name'     => 'required|string|max:255',
			'email'    => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:6|confirmed',
			'role_id'  => 'required|integer'
		])->validate();

		return User::create([
			'name'     => array_get($data, 'name'),
			'email'    => array_get($data, 'email'),
			'role_id'    => array_get($data, 'role_id'),
			'password' => bcrypt(array_get($data, 'password')),
		]);
	}

	/**
	 * Show the form for editing the specified user.
	 *
	 * @param  int $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function edit($id)
	{
		return $this->respond([
			'user'  => User::find($id),
			'roles' => UserRole::pluck('name', 'id')
		]);
	}

	/**
	 * Update the specified user in storage.
	 *
	 * @param $id
	 */
	public function update($id)
	{
		$data = request()->all();
		Validator::make(request()->all(), [
			'name' => 'required|between:3,60',
			'email' => 'required|email|max:255|unique:users,email,' . $id
		])->validate();

		User::find($id)->update($data);
	}

	/**
	 * Remove the specified user from storage.
	 *
	 * @param  int $id
	 */
	public function destroy($id)
	{
		User::destroy($id);
	}

	/**
	 * Change user's password
	 *
	 * @param $id
	 */
	public function changePassword($id)
	{
		$data = request()->all();

		Validator::make($data, [
			'password' => 'required|confirmed|min:6'
		])->validate();

		$user = User::find($id);

		// update password in the table User
		$user->update([
			'password' => bcrypt(array_get($data, 'password'))
		]);
	}

}
