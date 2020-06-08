<?php 

namespace Armincms\Targomaan\Concerns;

use Illuminate\Support\Str;

trait InteractsWithTargomaan
{ 
    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
    	if(Str::contains($key, '::')) {
    		list($key, $locale) = explode('::', $key);

    		return $this->targoman()->getTranslation($this, $key, null, $locale);
    	} 

    	return parent::getAttribute($key);  
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
    	if(Str::contains($key, '::')) {
    		list($key, $locale) = explode('::', $key);  

    		return $this->targoman()->setTranslation($this, $key, $value, $locale);
    	}

    	return parent::setAttribute($key, $value); 
    }

    public function targoman()
    {
    	return app('targoman')->driver($this->translator());
    }
}