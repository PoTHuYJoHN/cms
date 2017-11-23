<?php

use Webkid\Cms\Repositories\FileRepository;

/*
|--------------------------------------------------------------------------
| Default configuration. Better avoid editing
|--------------------------------------------------------------------------
*/
define('FILE_DEFAULT', 'default');

define('IMAGE_SIZE_ORIGINAL', 'original');
define('IMAGE_SIZE_MICRO','micro');
define('IMAGE_SIZE_TINY','tiny');
define('IMAGE_SIZE_FULLSIZE','fullsize');
define('IMAGE_SIZE_PREVIEW','preview');

define('FILE_EXT_IMAGE', 1);
define('FILE_EXT_DOC', 2);
define('FILE_EXT_IMG_AND_DOC', 3);

/*
|--------------------------------------------------------------------------
| Add your types and sizes below
|--------------------------------------------------------------------------
*/



return [
	'assets_dir'        => 'assets', // Directory where assets are stored
	'original_filename' => 'original', // Original file name
	'library'           => 'imagick', //gmagick, gd
	'stubs_dir'         => 'images/stubs',

	'dir'               => [
//		FILE_DEFAULT => 'file_directory',
	],
	'sizes'             => [
		FILE_DEFAULT      => [

			IMAGE_SIZE_TINY        => [
				'width'   => 100,
				'height'  => 100,
				'type'    => 'crop',
				'format'  => 'jpg',
				'quality' => 90
			],
			IMAGE_SIZE_MICRO       => [
				'width'   => 25,
				'height'  => 25,
				'type'    => 'crop',
				'format'  => 'jpg',
				'quality' => 95
			],
			IMAGE_SIZE_PREVIEW     => [
				'width'   => 100,
				'height'  => 100,
				'type'    => 'crop',
				'format'  => 'jpg',
				'quality' => 95
			],
			IMAGE_SIZE_FULLSIZE    => [
				'width'   => 900,
				'height'  => null,
				'type'    => 'scale',
				'format'  => 'jpg',
				'quality' => 95
			],

			/**
			 * Place your custom sizes here
			 */
		],

		/**
		 * Map your file type with sizes
		 */
//		FILE_DEFAULT         => [
//			IMAGE_SIZE_TINY     => [],
//			IMAGE_SIZE_FULLSIZE => [],
//			IMAGE_SIZE_PREVIEW  => [],
//		],
	],
	'allow'             => [
		'default'               => []
	],
	'rules'             => [
		/**
		 * Add rules for each file type
		 */
//		FILE_DEFAULT        => [
//			'single' => false,
//			'type'   => FILE_EXT_IMAGE
//		],
	],
	'types'             => [
		FILE_EXT_IMAGE => ['jpg', 'png', 'gif', 'jpeg', 'bmp', 'tif', 'tiff'],
		FILE_EXT_DOC   => ['doc', 'docx', 'xls', 'xlsx', 'pdf', 'txt']
	]
];
