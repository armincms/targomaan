<?php

namespace Armincms\Targomaan\Tests\Fixtures;

use Armincms\Targomaan\Concerns\InteractsWithTargomaan;
use Armincms\Targomaan\Contracts\Translatable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements Translatable
{
    use InteractsWithTargomaan;

    /**
     * Driver name of the Targomaan.
     *
     * @return [type] [description]
     */
    public function translator(): string
    {
        return 'layeric';
    }
}
