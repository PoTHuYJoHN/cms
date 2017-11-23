<?php

//'fields' required options : type, label. Other are optional: rules, editor, position
//'gallery' required options : fileType. Todo max quantity of files
//'list_title' => 'title', Used for list items title
// 'old_url' => true , Used to set old url. Useful for migration from another platform, e.g. WordPress
//'slug' generates slug from exact field

$seoFields = [
	'seo_description' => [
		'rules'    => 'max:160|min:10',
		'type'     => 'textarea',
		'label'    => 'SEO description',
		'editor'   => false,
		'position' => 99,
	],
	'seo_title'       => [
		'rules'    => 'max:70|min:3',
		'type'     => 'textbox',
		'label'    => 'SEO title',
		'editor'   => false,
		'position' => 98,
	]
];

$data = [

	// SAMPLE PAGES FOR EXAMPLE
	'home'        => [
		'fields'  => [
				'top_text'       => [
//				'rules' => 'string',
					'type'     => 'textbox',
					'label'    => 'Top text',
					'editor'   => false,
					'position' => 3,
				],
				'content_text_1' => [
					'type'     => 'textarea',
					'label'    => 'Content text left',
					'editor'   => true,
					'position' => 4,
				],
				'content_text_2' => [
					'type'     => 'textarea',
					'label'    => 'Content text right',
					'editor'   => true,
					'position' => 5,
				],
				'about_text'     => [
					'type'     => 'textarea',
					'label'    => 'About text',
					'editor'   => true,
					'position' => 6,
				],
				'video_url'      => [
					'type'     => 'textbox',
					'label'    => 'Video url',
					'position' => 7,
					'rules'    => 'url',
				]
			] + $seoFields,
	],
	'home_slider' => [
		'fields'     => [
			'title' => [
				'rules'    => 'max:500|min:10',
				'type'     => 'textbox',
				'label'    => 'Slide title',
//				'editor'   => false,
//				'datetime' => true,
//				'date'     => true,
//				'time'     => false,
				'position' => 1,
			],
		],
//		'coverPhoto' => [
//			'fileType' => FILE_DEFAULT
//		],
//		'gallery' => [
//			'fileType' => FILE_DEFAULT
//		],
		'slug'       => 'title', //if needed generate slug from field 'title'
		'isMultiple' => true,
		'old_url'    => true,
		'parent'     => 'home'
	],
//	'about'       => [
//		'fields' => [
//				'main' => [
//					'type'     => 'textarea',
//					'label'    => 'Maintext',
//					'editor'   => true,
//					'position' => 1,
//				]
//			] + $seoFields,
//	],
//	'about_team'  => [
//		'fields'     => [
//			'name'     => [
//				'type'     => 'textbox',
//				'label'    => 'Name',
//				'position' => 1,
//				'rules'    => 'max:100|min:3'
//			],
//			'position' => [
//				'type'     => 'textbox',
//				'label'    => 'Position',
//				'position' => 2,
//				'rules'    => 'max:100|min:3'
//			],
//			'email'    => [
//				'type'     => 'textbox',
//				'label'    => 'Email',
//				'position' => 3,
//				'rules'    => 'max:128|min:3|email'
//			],
//			'phone'    => [
//				'type'     => 'textbox',
//				'label'    => 'Phone',
//				'position' => 4,
//				'rules'    => 'max:128|min:2'
//			]
//		],
//		'coverPhoto' => [
//			'fileType' => \App\Repositories\FileRepository::FILE_ABOUT_TEAM
//		],
//		'isMultiple' => true,
//		'parent'     => 'about'
//	],
//
//	'technology' => [
//		'fields' => [
//				'no_question_text' => [
//					'type'     => 'textbox',
//					'label'    => 'No question text',
//					'editor'   => false,
//					'position' => 1,
//				]
//			] + $seoFields,
//	],
//
//	'technology_faq' => [
//		'fields'     => [
//			'question' => [
//				'type'     => 'textbox',
//				'label'    => 'Question',
//				'position' => 1,
//				'rules'    => 'max:100|min:3',
//			],
//			'answer'   => [
//				'type'     => 'textarea',
//				'label'    => 'Answer',
//				'editor'   => true,
//				'position' => 2,
//			]
//		],
//		'isMultiple' => true,
//		'parent'     => 'technology'
//	],
//
//	'content' => [
//		'fields' => [
//				'main_text' => [
//					'type'     => 'textarea',
//					'label'    => 'Main text',
//					'editor'   => true,
//					'position' => 1,
//				],
//				'stem_1'    => [
//					'type'     => 'textarea',
//					'label'    => 'Stem part 1',
//					'editor'   => true,
//					'position' => 2,
//				],
//				'stem_2'    => [
//					'type'     => 'textarea',
//					'label'    => 'Stem part 2',
//					'editor'   => true,
//					'position' => 3,
//				],
//				'video_url' => [
//					'type'     => 'textbox',
//					'label'    => 'Video url',
//					'position' => 4,
//					'rules'    => 'url',
//				],
//			] + $seoFields,
//	],
//
//	'contact' => [
//		'fields' => [
//				'contact_top_text' => [
//					'type'     => 'textbox',
//					'label'    => 'Top text',
//					'position' => 1,
//				]
//			] + $seoFields,
//	]
];

return $data;

