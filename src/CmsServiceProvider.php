<?php

namespace Webkid\Cms;

use Illuminate\Support\ServiceProvider;
use Webkid\Cms\Commands\CreateAdmin;

class CmsServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->loadRoutesFrom(__DIR__.'/routes.php');
		$this->loadMigrationsFrom(__DIR__ . '/../migrations');

		$viewPath = __DIR__.'/../resources/views';
		$this->loadViewsFrom($viewPath, 'cms');
		$this->publishes([
			$viewPath => base_path('resources/views/vendor/cms'),
		], 'views');

		$this->publishes([
			__DIR__.'/../assets' => public_path('vendor/cms'),
		], 'public');

		$this->publishes([
			__DIR__.'/../config/files.php' => config_path('files.php'),
			__DIR__.'/../config/langs.php' => config_path('langs.php'),
			__DIR__.'/../config/pages.php' => config_path('pages.php'),
		], 'config');

		if ($this->app->runningInConsole()) {
			$this->commands([
				CreateAdmin::class
			]);
		}
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
}
