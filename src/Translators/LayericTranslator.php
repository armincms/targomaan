<?php

namespace Armincms\Targomaan\Translators;

use Armincms\Targomaan\Contracts\Translator;
use Armincms\Targomaan\Translation;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class LayericTranslator implements Translator
{
    use InteractsWithTranslations;

    protected static $translations = [];

    public function saved($model)
    {
        collect($model->getTranslationChanges())->filter()->map(function ($attributes, $locale) use ($model) {
            $model::unguarded(function () use ($model, $attributes, $locale) {
                $model->translations()->updateOrCreate([
                    $this->getLocaleKeyName($model) => $locale,
                ], (array) $attributes);
            });
        });
    }

    /**
     * Set the translation attribute value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  string  $locale
     * @param  mixed  $value
     */
    public function setTranslation($model, string $key, string $locale, $value)
    {
        $model->setTranslationChanges($key, $locale, $value);

        return $this;
    }

    /**
     * Get the translation attribute value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  string  $locale
     * @param  mixed  $default
     * @return mixed
     */
    public function getTranslation($model, string $key, string $locale, $default = null)
    {
        if (in_array($key, [$this->getLocaleKeyName($model), $model->getKeyName()])) {
            return $model->getOriginal($key);
        }

        return data_get(
            $this->getTranslationForLocale($model, $locale), $key, $default
        );
    }

    /**
     * Get the translation isntance for the gicen locale.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $locale
     * @return Null\Illuminate\Database\Eloquent\Model $model
     */
    public function getTranslationForLocale($model, string $locale)
    {
        return $model->translations->where($this->getLocaleKeyName($model), $locale)->first();
    }

    /**
     * Handle translations relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function handleTranslations($model)
    {
        $instance = $this->newTranslationModel($model);

        return new HasMany(
            $instance->newQuery(), $model, $instance->getTable().'.'.$model->getForeignKey(), $model->getKeyName()
        );
    }

    /**
     * Create instanceof of the "translation" model.
     *
     * @return string
     */
    protected function newTranslationModel($model)
    {
        $class = $this->getTranslationModel($model);

        return tap(new $class, function ($instance) use ($model) {
            $instance->setTable($this->getTranslationTable($model));
        });
    }

    /**
     * Get the class of the "translation" model.
     *
     * @return string
     */
    protected function getTranslationModel($model)
    {
        return defined(get_class($model).'::TRANSLATION_MODEL') ? $model::TRANSLATION_MODEL : Translation::class;
    }

    /**
     * Get the table name of the "translation".
     *
     * @return string
     */
    protected function getTranslationTable($model)
    {
        if (defined(get_class($model).'::TRANSLATION_TABLE')) {
            return $model::TRANSLATION_TABLE;
        }

        return Str::snake(Str::pluralStudly(
            class_basename($model).'Translation'
        ));
    }

    /**
     * Convert the model instance to an array.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  array  $attributes
     * @return array
     */
    public function toArray($model, array $attributes): array
    {
        if ($translation = $this->getTranslationForLocale($model, app()->getLocale())) {
            return array_merge($translation->toArray(), $attributes);
        }

        return $attributes;
    }
}
