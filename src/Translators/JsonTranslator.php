<?php 

namespace Armincms\Targomaan\Translators;

use Armincms\Targomaan\Contracts\Translator; 
 
class JsonTranslator implements Translator
{
	public function setTranslation($model, $key, $value, $locale)
	{ 
		$translations = $this->getTranslations($model, $key);

		$model->{$key} = array_merge((array) $translations, [
			$locale => $value
		]); 

		return $this;
	}

	public function getTranslation($model, $key, $default, $locale)
	{ 
		return data_get($model, "{$key}.{$locale}", $default); 
	} 

	public function getTranslations($model, $key)
	{ 
		return (array) data_get($model, $key); 
	} 
}