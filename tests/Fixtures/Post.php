<?php

namespace Armincms\Targomaan\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Armincms\Targomaan\Contracts\Translatable;
use Armincms\Targomaan\Concerns\InteractsWithTargomaan;

class Post extends Model implements Translatable
{ 
	use InteractsWithTargomaan;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    	'name' => 'json',
    ];

	/**
	 * Driver name of the targomaan.
	 * 
	 * @return [type] [description]
	 */
	public function translator(): string
	{
		return 'json';
	}
}
