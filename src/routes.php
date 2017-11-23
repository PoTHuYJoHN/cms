<?php

/*
|--------------------------------------------------------------------------
| DASHBOARD API
|--------------------------------------------------------------------------
*/

use Webkid\Cms\Controllers\Dashboard\UsersController;

Route::group([
	'prefix'     => 'api',
	'middleware' => ['web', 'auth', \Webkid\Cms\Middleware\Dashboard::class]
], function () {

	Route::put('landing/updateAllLangs/{id}', 'Webkid\Cms\Controllers\Dashboard\LandingPagesController@updateAllLangs');
	Route::get('landing/listByParent/{parentId}/{section}', 'Webkid\Cms\Controllers\Dashboard\LandingPagesController@listByParent');
	Route::get('landing/editByToken/{token}', 'Webkid\Cms\Controllers\Dashboard\LandingPagesController@editByToken');
	Route::get('landing/getByTokenAndSection/{token}/{section}', 'Webkid\Cms\Controllers\Dashboard\LandingPagesController@getByTokenAndSection');
	Route::resource('landing', 'Webkid\Cms\Controllers\Dashboard\LandingPagesController');
	Route::get('landing/create/{section}', 'Webkid\Cms\Controllers\Dashboard\LandingPagesController@create');
	Route::post('landing/{id}/updateOldUrl', 'Webkid\Cms\Controllers\Dashboard\LandingPagesController@updateOldUrl');
//
//	// Settings
	Route::post('settings/updateAll', 'Webkid\Cms\Controllers\Dashboard\SettingsController@updateAll');
	Route::resource('settings', \Webkid\Cms\Controllers\Dashboard\SettingsController::class);
//
//	// Users
	Route::post('users/changePassword/{id}', 'Webkid\Cms\Controllers\Dashboard\UsersController@changePassword');

	Route::resource('users', UsersController::class);

	// Subscribers
//	Route::get('subscribers', 'Api\Dashboard\SubscribersController@index');
//	Route::delete('subscribers/{id}', 'Api\Dashboard\SubscribersController@destroy');
});

/*
|--------------------------------------------------------------------------
| COMMON API
|--------------------------------------------------------------------------
*/
Route::group([
	'prefix' => 'api/common',
	'middleware' => ['web']
], function () {
	Route::resource('files', \Webkid\Cms\Controllers\Api\FilesController::class);
	Route::delete('files/forceDelete/{token}', 'Webkid\Cms\Controllers\Api\FilesController@forceDelete');
});


/*
|--------------------------------------------------------------------------
| API MIXED CALLS. WITH AUTH AND NOT
|--------------------------------------------------------------------------
*/
//Route::group([
//	'prefix' => 'api/auth'
//], function() {
//	Route::get('profile', 'Api\Auth\ProfileController@index');
//});


/*
|--------------------------------------------------------------------------
| DASHBOARD ANGULAR FIX
|--------------------------------------------------------------------------
*/
//dd(auth()->user());

Route::group([
	'middleware' => ['web']
], function () {
	Route::get('/dashboard', [
		'middleware' => \Webkid\Cms\Middleware\Dashboard::class,
		'uses' => 'Webkid\Cms\Controllers\DashboardController@index'
	]);
	Route::get('/dashboard/{one?}/{two?}/{three?}/{four?}/{five?}', [
		'middleware' => \Webkid\Cms\Middleware\Dashboard::class,
		'uses' => 'Webkid\Cms\Controllers\DashboardController@index'
	]);

	Route::get('cfg', 'Webkid\Cms\Controllers\ConfigController@index');
});






/*
|--------------------------------------------------------------------------
| OTHER SPECIAL ROUTES
|--------------------------------------------------------------------------
*/
//Route::get('download/{token}', 'FilesController@download');
