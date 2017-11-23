<?php


namespace Webkid\Cms\Repositories;


use Webkid\Cms\Models\LandingPage;
use Webkid\Cms\Models\LandingPageField;
use Webkid\Cms\Models\LandingPageFieldsText;

/**
 * Class LandingPageFieldRepository
 *
 * @package App\Repositories
 */
class LandingPageFieldRepository
{
	//todo REFACTOR TO createFields
	/**
	 *
	 * @param $options
	 * @param $pageId
	 */
	public function fillFields($options, $pageId)
	{
		foreach($options as $option) {
			//create field
			$option['page_id'] = $pageId;
			$values = $option['value'];

			unset($option['value']);
			$field = LandingPageField::create($option);

			//create lang values
			foreach($values as $lang => $value) {
				$fieldText = LandingPageFieldsText::create([
					'field_id' => $field->id,
					'lang' => $lang,
					'value' => $value
				]);

				$fieldText->save();
			}
		}
	}


	/**
	 * @param $pageId
	 * @param $data
	 */
	public function createFields($pageId, $data)
	{
		foreach($data['items'] as $langFields) {
			foreach($langFields['fields'] as $key => $val) {
				$field = LandingPageField::whereRaw('page_id = ? AND `key` = ?', [$pageId, $key])->first();
				if(!$field) {
					$fieldData = $val;
					$fieldData['key'] = $key;
					$fieldData['page_id'] = $pageId;
					$field = LandingPageField::create($fieldData);
				}


				LandingPageFieldsText::create([
					'field_id' => $field->id,
					'lang' => $langFields['lang'],
					'value' => $val['value']
				]);
			}
		}


	}
	/**
	 * @param $pageId
	 * @param $lang
	 * @param $fields
	 */
	public function updateFields($pageId, $lang, $fields)
	{
		foreach($fields as $key => $val) {
			$field = LandingPageField::whereRaw('page_id = ? AND `key` = ?', [$pageId, $key])->first();

			if(!$field) { //field was not found yet. Maybe changed config or smth like that.
				$page = LandingPage::findOrFail($pageId);
				//get token and other stuff from config
				$config = \Config::get('pages.' .$page->token . '.fields');

				if(!$config) {
					$config = \Config::get('pages.' .$page->section . '.fields');
				}

				if($config) {
					//create field
					$fieldConfig = $config[$key];

					$field = LandingPageField::create([
						'page_id' => $pageId,
						'key' => $key,
						'type' => $fieldConfig['type'],
						'label' => $fieldConfig['label'],
						'editor' => !empty($fieldConfig['editor']) ? $fieldConfig['editor'] : false,
						'position' => !empty($fieldConfig['position']) ? $fieldConfig['position'] : false,
					]);
				}


			}

			if($field) {
				//update field
				$fieldText = LandingPageFieldsText::whereRaw('field_id = ? AND lang = ?', [$field->id, $lang])->first();

				if(!$fieldText) { //create field texts
					$fieldText = LandingPageFieldsText::create([
							'field_id' => $field->id,
							'lang' => $lang,
							'value' => $val
					]);
				}

				$fieldText->value = $val;
				$fieldText->save();
			}
		}

	}
}
