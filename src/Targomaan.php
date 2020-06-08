<?php

namespace Armincms\Targomaan;
 
use InvalidArgumentException;
use Armincms\Targomaan\Contracts\Translator;
use Armincms\Targomaan\Translators\JsonTranslator;
use Armincms\Targomaan\Translators\AssocTranslator;
use Armincms\Targomaan\Translators\SeparateTranslator;
use Illuminate\Support\Manager;
 
class Targomaan extends Manager
{   
    /**
     * Create a new driver instance.
     *
     * @param  string  $driver
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function createDriver($driver)
    {
    	$instance = parent::createDriver($driver); 

    	if($instance instanceof Translator) {
    		return $instance;
    	}

        throw new InvalidArgumentException("
        	Driver [$driver] should implement the `Armincms\Targomaan\Contracts\Translator` interface.
        ");
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
    	return config('targomaan.default', 'json');
    }

    /**
     * Create JSON driver instance.
     * 
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function createJsonDriver()
    {
    	return new JsonTranslator;
    }

    /**
     * Create Assoc driver instance.
     * 
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function createAssocDriver()
    {
        return new AssocTranslator;
    }

    /**
     * Create Separate driver instance.
     * 
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function createSeparateDriver()
    {
        return new SeparateTranslator;
    }
} 