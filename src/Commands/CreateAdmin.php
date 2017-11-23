<?php

namespace Webkid\Cms\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'cms:admin:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create admin user';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$email = $this->ask('Provide email of admin');
		$name = $this->ask('Provide name of admin');
		$password = $this->secret('Provide password of admin');

		$data = compact('email','name', 'password');

		$validator = Validator::make($data, [
			'name'     => 'required|max:32',
			'email'    => 'required|max:128|email|unique:users,email', // Unique email in users table
			'password' => 'required|max:32|min:6',
		]);

		if ($validator->fails()) {
			$errorMessage = $validator->getMessageBag();

			$this->error($errorMessage);

			$this->error('Try again with proper data');

			return;
		}

		try {
			User::create([
				'name'     => array_get($data, 'name'),
				'email'    => array_get($data, 'email'),
				'role_id'  => 2, //todo add constant
				'password' => bcrypt(array_get($data, 'password')),
			]);

			$this->info('User has been created successfully!');
		} catch (\Exception $e) {
			$this->error('Something went wrong');
		}
	}
}
