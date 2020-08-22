<?php 

namespace Armincms\Targomaan\Translators;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Armincms\Targomaan\Contracts\Serializable;
use Armincms\Targomaan\Contracts\Translator; 
 
class JsonTranslator implements Translator, Serializable
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
				$value = $this->setMutatedAttributeValue($model, $key, $value);
			}  

			$values = array_merge((array) json_decode(data_get($model->getAttributes(), $key)), [
				$locale => $value
			]);  

			$model->{$key} = $this->isJsonCastable($model, $key) 
								? $values : $this->castAttributeAsJson($key, $values); 
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
		$value = $model::withoutTranslation(function() use ($model, $key, $locale) {    
			if(($values = $model->getAttribute($key)) && ! $this->isJsonCastable($model, $key)) { 
				$values = $model->fromJson(data_get($model->getAttributes(), $key));
			}     

			return array_key_exists($locale, (array) $values) ? $values[$locale] : new \stdClass;
		});      

		if(! is_object($value) && $model->hasGetMutator($key)) { 
			return $this->mutateAttribute($model, $key, $value);
		} 

		return is_object($value) ? $default : $value; 
	}  

    /**
     * Set the value of an attribute using its mutator.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function setMutatedAttributeValue($model, $key, $value)
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

    /**
     * Determine whether a value is JSON castable for inbound manipulation.
     *
     * @param  string  $key
     * @return bool
     */
    protected function isJsonCastable($model, $key)
    {
        return $model->hasCast($key, ['array', 'json', 'object', 'collection']);
    }

    /**
     * Cast the given attribute to JSON.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return string
     */
    protected function castAttributeAsJson($key, $value)
    {
        $value = $this->asJson($value);

        if ($value === false) {
            throw JsonEncodingException::forAttribute(
                $this, $key, json_last_error_msg()
            );
        }

        return $value;
    }

    /**
     * Encode the given value as JSON.
     *
     * @param  mixed  $value
     * @return string
     */
    protected function asJson($value)
    {
        return json_encode($value);
    }
}