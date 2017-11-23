<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('key', 128)->unique();
			$table->string('label', 128);
			$table->text('value')->nullable();
			$table->string('type', 32)->default('textbox');
			$table->boolean('isRoot')->default('0');
			$table->integer('position')->nullable()->unsigned();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('settings');
	}

}
