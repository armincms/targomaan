<?php 

namespace Armincms\Targomaan\Translators;
 
 
trait InteractsWithTranslations 
{    
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
