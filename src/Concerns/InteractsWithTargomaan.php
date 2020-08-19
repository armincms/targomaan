<?php 

namespace Armincms\Targomaan\Concerns;

use Illuminate\Database\Eloquent\Scope;
use Armincms\Targomaan\Contracts\Translator;
use Illuminate\Support\Str;
use Closure;

trait InteractsWithTargomaan
{  
    /**
     * Detect if the model using translation.
     * 
     * @var boolean
     */
    protected static $forcedTranslation = true; 

    /**
     * The locale string delimiter.
     * 
     * @var string
     */
    protected static $delimiter = "::";

    /**
     * Handle events with translators.
     * 
     * @return void
     */
    public static function bootInteractsWithTargomaan()
    { 
        static::observe($targomaan = (new static)->targomaan());

        $targomaan instanceof Scope && static::addGlobalScope($targomaan); 
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
        if(Str::contains($key, static::delimiter())) {
            list($key, $locale) = explode(static::delimiter(), $key);  

            return $this->targomaan()->setTranslation($this, $key, $locale, $value);
        }

        return parent::setAttribute($key, $value); 
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
    	if(Str::contains($key, static::delimiter())) {
    		list($key, $locale) = explode(static::delimiter(), $key);

    		return $this->targomaan()->getTranslation($this, $key, $locale);
    	}

        if(is_null($value = parent::getAttribute($key)) && static::shouldTranslation()) { 
            return $this->getAttribute($this->localizeKey($key));
        }  

        return $value;
    } 

    /**
     * Append the localize string to the key.
     * 
     * @param  string      $key    
     * @param  string|null $locale 
     * @return string              
     */
    public function localizeKey(string $key, string $locale = null)
    { 
        if(Str::endsWith($key, static::delimiter().$locale)) {
            return $key;
        }

        // Determine if contains another locale key
        if(Str::contains($key, static::delimiter())) {
            $key = Str::before($key, static::delimiter());

            return $this->localizeKey($key, $locale);
        }

        return $key . static::delimiter() . ($locale ?? app()->getLocale());
    }

    /**
     * Get the locale string delimiter.
     * 
     * @return string
     */
    public static function delimiter() : string
    {
        return static::$delimiter;
    }

    /**
     * Run the given callable while ignored forced-translation.
     *
     * @param  callable  $callback
     * @return mixed
     */
    public static function withoutTranslation(Closure $callback)
    {
        if (! static::shouldTranslation()) {
            return $callback();
        }

        static::forcedTranslation(false);

        try {
            return $callback();
        } finally {
            static::forcedTranslation(true);
        } 
    }

    /**
     * Determine if current state is "forcedTranslation".
     * 
     * @return boolean
     */
    public static function shouldTranslation() : bool
    {
        return static::$forcedTranslation;
    }

    /**
     * Determine if attributes should be translated.
     * 
     * @param  bool|boolean $forced 
     * @return $this                          
     */
    public static function forcedTranslation(bool $forced = true)
    {
        static::$forcedTranslation = $forced;

        return new static;
    } 

    /**
     * Get the attribute translate value.
     * 
     * @param  string      $key     
     * @param  mixed      $default 
     * @param  string|null $locale  
     * @return mixed               
     */
    public function getTranslation($key, $default = null, string $locale = null)
    {    
        return $this->getAttribute($this->localizeKey($key, $locale)) ?? $default;
    }

    /**
     * Set the translation attribute value.
     *   
     * @param string $key    
     * @param mixed $value  
     * @param string $locale 
     */
    public function setTranslation(string $key, $value, string $locale = null)
    {   
        return $this->setAttribute($this->localizeKey($key, $locale), $value);
    } 

    /**
     * Get the translations models.
     * 
     * @return mixed
     */
    public function translations()
    {
        return $this->targomaan()->handleTranslations($this);
    }

    /**
     * Get the 'Targomaan' instance.
     *  
     * @return \Armincms\Targomaan\Contracts\Translator
     */
    public function targomaan() : Translator
    {
    	return app('targomaan')->driver($this->translator());
    } 

    /**
     * Get the targomaan driver.
     * 
     * @return string
     */
    protected function translator() : string
    {
        return property_exists($this, 'translator')? $this->translator : app('targomaan')->getDefaultDriver();
    }
}