<?php

namespace Armincms\Targomaan\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Armincms\Targomaan\Contracts\Translatable;
use Armincms\Targomaan\Concerns\InteractsWithTargomaan;

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
		return 'assoc';
	}

	public function translations()
	{
		return $this->hasMany(static::class, 'assoc_key', 'assoc_key');
	}
}
