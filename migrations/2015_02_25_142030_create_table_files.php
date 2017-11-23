<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFiles extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('files', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type', 64)->index();
			$table->integer('parent_id', false, true)->nullable();
			$table->integer('user_id', false, true)->nullable();
			$table->string('token', 8)->unique();
			$table->string('name')->default('');
			$table->string('ext', 10)->default('');
			$table->boolean('isImage')->default('1');
			$table->integer('size', false, true)->default('0');
			$table->text('cropArea')->nullable();
			$table->timestamps();

			$table->foreign('user_id')
				->references('id')->on('users')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('files', function($table)
		{
			$table->dropForeign('files_user_id_foreign');

		});

		Schema::drop('files');
	}

}
