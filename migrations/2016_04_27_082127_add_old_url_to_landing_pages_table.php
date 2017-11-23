<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOldUrlToLandingPagesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('landing_pages', function (Blueprint $table) {
			$table->string('old_url')
				->nullable()
				->after('slug')
			;
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('landing_pages', function (Blueprint $table) {
			$table->dropColumn(['old_url']);
		});
	}
}
