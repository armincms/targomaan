<?php 

namespace Armincms\Targomaan\Translators;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Armincms\Targomaan\Contracts\Translator; 
use Armincms\Targomaan\Translation; 
 
class LayericTranslator implements Translator
{  
	public function saved($model)
	{   
		$models = collect($model->getHidden())->filter()->map(function($attributes, $locale) use ($model) { 
			$model::unguarded(function() use ($model, $attributes, $locale) {   
				$model->translations()->updateOrCreate([
					$this->getLocaleKeyName($model) => $locale
				], (array) $attributes); 
			});
		});

		$model->setHidden([]);
	}

	/**
	 * Set the translation attribute value.
	 * 
	 * @param \Illuminate\Database\Eloquent\Model $model  
	 * @param string $key    
	 * @param string $locale 
	 * @param mixed $value  
	 */
	public function setTranslation($model, string $key, string $locale, $value)
	{   
		$model->setHidden(with($model->getHidden(), function($translations) use ($locale, $key, $value) {
			return data_set($translations, "{$locale}.{$key}", $value);
		})); 

		return $this;
	} 

	/**
	 * Get the translation attribute value.
	 * 
	 * @param  \Illuminate\Database\Eloquent\Model $model  
	 * @param  string $key       
	 * @param  string $locale  
	 * @param  mixed $default 
	 * @return mixed          
	 */
	public function getTranslation($model, string $key, string $locale, $default = null)
	{    
		if(in_array($key, [$this->getLocaleKeyName($model), $model->getKeyName()])) {
			return $model->getOriginal($key);
		}

		$model->relationLoaded('translations') || $model->load('translations'); 
		
		return data_get(
			$model->translations->where($this->getLocaleKeyName($model), $locale)->first(), $key, $default
		); 
	}  

	/**
	 * Handle translations relationship.
	 * 
	 * @param  \Illuminate\Database\Eloquent\Model $model 
	 * @return \Illuminate\Database\Eloquent\Relations\Relation  
	 */
	public function handleTranslations($model)
	{   
		$instance = $this->newTranslationModel($model); 

        return new HasMany(
        	$instance->newQuery(), $model, $instance->getTable().'.'.$model->getForeignKey(), $model->getKeyName()
        ); 
	}   

    /**
     * Create instanceof of the "translation" model.
     *
     * @return string
     */
	protected function newTranslationModel($model)
	{
		$class = $this->getTranslationModel($model);

		return tap(new $class, function($instance) use ($model) { 
			$instance->setTable($this->getTranslationTable($model)); 
		}); 
	}

    /**
     * Get the class of the "translation" model.
     *
     * @return string
     */
	protected function getTranslationModel($model)
	{
		return defined(get_class($model).'::TRANSLATION_MODEL') ? $model::TRANSLATION_MODEL : Translation::class; 
	} 

    /**
     * Get the table name of the "translation".
     *
     * @return string
     */
	protected function getTranslationTable($model)
	{
		if(defined(get_class($model).'::TRANSLATION_TABLE')) {
			return $model::TRANSLATION_TABLE;
		}

		return Str::snake(Str::pluralStudly(
			class_basename($model).'Translation'
		)); 
	}

    /**
     * Get the name of the "locale" column.
     *
     * @return string
     */
	protected function getLocaleKeyName($model) : string
	{
		return defined(get_class($model).'::LOCALE_KEY') ? $model::LOCALE_KEY : 'locale'; 
	}
}