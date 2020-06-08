<?php 

namespace Armincms\Targomaan\Translators;

use Armincms\Targomaan\Contracts\Translator; 
 
class AssocTranslator implements Translator
{
	public function setTranslation($model, $key, $value, $locale)
	{  
		$attributes = ['language' => $locale, $key => $value];

		$model::unguarded(function() use ($model, $key, $value, $locale) {
			$model->translations()->updateOrCreate(['language' => $locale], [
				$key => $value,
				'assoc_key' => $model->assoc_key,
			]);
		}); 

		return $this;
	}

	public function getTranslation($model, $key, $default, $locale)
	{ 
		if($translations = $model->translations) {
			return data_get($translations->where('language', $locale)->first(), $key, $default);
		}

		return $default; 
	}  
}