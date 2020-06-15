<?php

namespace Armincms\Targomaan\Tests\Fixtures;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Armincms\Targomaan\Contracts\Translatable;
use Armincms\Targomaan\Concerns\InteractsWithTargomaan;

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
