<?php

namespace Armincms\Targomaan;
 
use InvalidArgumentException;
use Armincms\Targomaan\Contracts\Translator;
use Armincms\Targomaan\Translators\JsonTranslator;
use Armincms\Targomaan\Translators\SequentialTranslator;
use Armincms\Targomaan\Translators\LayericTranslator;
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
     * Create the JSON driver instance.
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
     * Create Sequential driver instance.
     * 
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function createSequentialDriver()
    {
        return new SequentialTranslator;
    }

    /**
     * Create the Layeric driver instance.
     * 
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function createLayericDriver()
    {
        return new LayericTranslator;
    }
} 