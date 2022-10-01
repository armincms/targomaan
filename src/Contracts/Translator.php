<?php

namespace Armincms\Targomaan\Contracts;

interface Translator
{
    /**
     * Set the translation attribute value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  string  $locale
     * @param  mixed  $value
     */
    public function setTranslation($model, string $key, string $locale, $value);

    /**
     * Get the translation attribute value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  string  $locale
     * @param  mixed  $default
     * @return mixed
     */
    public function getTranslation($model, string $key, string $locale, $default = null);

    /**
     * Convert the model instance to an array.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  array  $attributes
     * @return array
     */
    public function toArray($model, array $attributes): array;
}
