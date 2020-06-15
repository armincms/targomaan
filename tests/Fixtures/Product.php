<?php

namespace Armincms\Targomaan\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Armincms\Targomaan\Contracts\Translatable;
use Armincms\Targomaan\Concerns\InteractsWithTargomaan;

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
