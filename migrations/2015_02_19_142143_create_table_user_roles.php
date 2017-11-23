<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserRoles extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->integer('role_id')->unsigned()->after('email');
		});

		Schema::create('user_roles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
		});

		DB::table('user_roles')->insert([
			[ 'name' => 'member' ],
			[ 'name' => 'admin' ]
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_roles');
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn(['role_id']);
		});
	}

}
