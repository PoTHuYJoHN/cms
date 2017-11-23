<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLandingPageFieldsText extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('landing_page_fields_text', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('field_id')->unsigned();
			$table->string('lang');
			$table->text('value')->nullable();

			$table->foreign('field_id')
				->references('id')
				->on('landing_page_fields')
				->onUpdate('cascade')
				->onDelete('cascade');

			$table->unique(['field_id', 'lang']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('landing_page_fields_text');
	}
}
