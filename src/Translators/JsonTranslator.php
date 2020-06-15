<?php 

namespace Armincms\Targomaan\Translators;

use Armincms\Targomaan\Contracts\Translator; 
use Illuminate\Support\Str;
 
class JsonTranslator implements Translator
{ 
	public function handleTranslations($model)
	{
		throw new \Exception("The 'json' translator cant handle translations");		
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
		$model::withoutTranslation(function() use ($model, $key, $value, $locale) { 
			if($model->hasSetMutator($key)) { 
				$value = $this->getMutatedAttributeValue($model, $key, $value);
			} 

			$attributes = data_get($model->getAttributes(), $key);

			$translations = array_merge((array) json_decode($attributes, true), [
				$locale => $value
			]);  

			$model->forceFill([
				$key => json_encode($translations)
			]);  
		}); 

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
		$value = $model::withoutTranslation(function() use ($model, $key, $default, $locale) {  
			$translations = (array) json_decode($model->getOriginal($key), true);

			if(json_last_error() !== JSON_ERROR_NONE || ! isset($translations[$locale])) { 
				return $default;
			} 

			return $translations[$locale];
		});   

		if($model->hasGetMutator($key)) {
			return $this->mutateAttribute($model, $key, $value);
		}

		return $value; 
	}  

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function getMutatedAttributeValue($model, $key, $value)
    {
        return $model->{'set'.Str::studly($key).'Attribute'}($value);
    } 

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function mutateAttribute($model, $key, $value)
    {
        return $model->{'get'.Str::studly($key).'Attribute'}($value);
    }
}