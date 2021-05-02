<?php 

namespace Armincms\Targomaan\Translators;
 
 
trait InteractsWithTranslations 
{    
	/**
	 * Get the translation isntance for the gicen locale.
	 * 
	 * @param  \Illuminate\Database\Eloquent\Model $model   
	 * @param  string $locale   
	 * @return Null\Illuminate\Database\Eloquent\Model $model       
	 */
	public function getTranslationForLocale($model, string $locale)
	{     
		$model->relationLoaded('translations') || $model->load('translations'); 
		
		return $model->translations
                     ->where($this->getLocaleKeyName($model), $locale)
                     ->where($model->getKeyName(), '!=', $model->getKey())->first();
	} 

    /**
     * Get the name of the "locale" column.
     *
	 * @param  \Illuminate\Database\Eloquent\Model $model 
     * @return string
     */
	protected function getLocaleKeyName($model) : string
	{
		return defined(get_class($model).'::LOCALE_KEY') ? $model::LOCALE_KEY : 'locale'; 
	}

    /**
     * Convert the model instance to an array.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model 
     * @param  array $attributes 
     * @return array
     */ 
    public function toArray($model, array $attributes): array
    {
    	if ($translation = $this->getTranslationForLocale($model, app()->getLocale())) {
    		return array_merge($translation->toArray(), $attributes);
    	}

    	return $attributes; 
    }
}
