<?php

namespace Armincms\Targomaan\Tests\Fixtures;

use Armincms\Targomaan\Concerns\InteractsWithTargomaan;
use Armincms\Targomaan\Contracts\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model implements Translatable
{
    use InteractsWithTargomaan;

    /**
     * Driver name of the targomaan.
     *
     * @return [type] [description]
     */
    public function translator(): string
    {
        return 'sequential';
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'order' => 'float',
    ];

    public function setTitleAttribute($name)
    {
        $this->attributes['title'] = Str::upper($name);
    }

    public function getTitleAttribute($title)
    {
        return Str::title($title);
    }
}
