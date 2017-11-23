<?php


namespace Webkid\Cms\Traits;


trait PageSeo
{
	/**
	 * @param $value
	 * @return mixed
	 */
	public function persistMetaTitle($value)
	{
		return $this->persistText($value);
	}

	/**
	 * @param $value
	 * @return mixed
	 */
	public function persistMetaDescription($value)
	{
		return $this->persistText($value);
	}

	/**
	 * @param $text
	 *
	 * @return mixed
	 */
	private function persistText($text)
	{
		return str_replace('"', '\'', htmlspecialchars_decode($text, ENT_QUOTES));
	}


	public function getSeoAttributeFromFields($fields, $attribute = 'seo_title')
	{
		$value = '';

		if(isset($fields[$attribute])) {
			$value = $this->persistMetaTitle($fields[$attribute]);
		}
		return $value;
	}
}
