<?php 

namespace Armincms\Targomaan\Contracts;

interface Translator
{
	public function setTranslation($model, $key, $value, $locale);
	public function getTranslation($model, $key, $default, $locale);
}