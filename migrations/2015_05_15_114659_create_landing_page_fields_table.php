<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLandingPageFieldsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('landing_page_fields', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('page_id')->unsigned();
			$table->string('key');
			$table->string('type');
			$table->string('label');
			$table->boolean('editor')->default('1');
			$table->integer('position')->unsigned()->default('0');

			$table->foreign('page_id')
				->references('id')
				->on('landing_pages')
				->onUpdate('cascade')
				->onDelete('cascade');

			$table->unique(['page_id', 'key']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('landing_page_fields');
	}
}