<?php 

namespace Armincms\Targomaan\Translators;

use Illuminate\Support\Str;
use Armincms\Targomaan\Contracts\Translator; 
 
class SequentialTranslator implements Translator
{  
	/**
	 * Hanldle saving event.
	 * 
	 * @param  \Illuminate\Database\Eloquent\Model $model 
	 * @return         
	 */
	public function saving($model)
	{ 
		$model::unguarded(function() use ($model) {  
			$sequenceKey = $this->getSequenceKeyName($model);

			if(empty($model->{$sequenceKey})) {
				$model->setAttribute($this->getSequenceKeyName($model), md5(time()));
			} 
		});
	}

	/**
	 * Hanldle saved event.
	 * 
	 * @param  \Illuminate\Database\Eloquent\Model $model 
	 * @return         
	 */
	public function saved($model)
	{  
		$models = collect($model->getHidden())->filter()->map(function($attributes, $locale) use ($model) { 
			$model::unguarded(function() use ($model, $attributes, $locale) { 

				$sequenceKey = $this->getSequenceKeyName($model);
				$localeKey   = $this->getLocaleKeyName($model);

				$model->translations()->updateOrCreate([
						$sequenceKey => $model->{$sequenceKey},
						$localeKey   => $locale,
					], 
					(array) $attributes
				); 
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
		if($locale === $model->{$this->getLocaleKeyName($model)}) {
			$model->{$key} = $value;
		} elseif ($locale == app()->getLocale() && is_null($model->{$this->getLocaleKeyName($model)})) {
			$model->{$this->getLocaleKeyName($model)} = $locale;
			
			return $this->setTranslation($model, $key, app()->getLocale(), $value);
		} else {  
			$model->setHidden(with($model->getHidden(), function($translations) use ($locale, $key, $value) {
				return data_set($translations, "{$locale}.{$key}", $value);
			}));  
		} 

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
		if(in_array($key, [$this->getSequenceKeyName($model), $this->getLocaleKeyName($model)])) {
			return $model->getOriginal($key);
		}

		! $model::shouldTranslation() || $model->relationLoaded('translations') || $model->load('translations');

		$translation = $model->translations->where($this->getLocaleKeyName($model), $locale)->first();
		
		return $model->withoutTranslation(function() use ($translation, $key, $default) {
			return data_get($translation, $key, $default); 
		}); 
	}  

	/**
	 * Handle translations relationship.
	 * 
	 * @param  \Illuminate\Database\Eloquent\Model $model 
	 * @return \Illuminate\Database\Eloquent\Relations\Relation  
	 */
	public function handleTranslations($model)
	{
		$sequenceKey = $this->getSequenceKeyName($model);
		$localeKey   = $this->getLocaleKeyName($model);

		return $model
					->hasMany($model, $sequenceKey, $sequenceKey)
					->where(function($q) use ($model) {
						$id = $model::withoutTranslation(function() use ($model) {
							return $model->getAttribute('id');
						}); 

						return $q->where($q->qualifyColumn('id'), '!=', $id); 
					});
	}  

    /**
     * Get the name of the "sequence key" column.
     *
	 * @param  \Illuminate\Database\Eloquent\Model $model 
     * @return string
     */
	protected function getSequenceKeyName($model) : string
	{
		return defined(get_class($model).'::SEQUENCE_KEY') ? $model::SEQUENCE_KEY : 'sequence_key'; 
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
}