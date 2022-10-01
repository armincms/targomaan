<?php

namespace Armincms\Targomaan\Tests\Fixtures;

use Armincms\Targomaan\Concerns\InteractsWithTargomaan;
use Armincms\Targomaan\Contracts\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model implements Translatable
{
    use InteractsWithTargomaan;

    /**
     * Driver name of the targomaan.
     *
     * @return [type] [description]
     */
    public function translator(): string
    {
        return 'json';
    }

    public function getTitleAttribute($value)
    {
        return Str::title($value);
    }
}
