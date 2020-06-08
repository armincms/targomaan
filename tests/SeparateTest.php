<?php 

namespace Armincms\Targomaan\Tests; 

use Armincms\Targomaan\Tests\Fixtures\Product;

class SeparateTest extends AggregateTest
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp(): void
	{
	    parent::setUp(); 

	    $product = factory(Product::class)->create();
 
	    $product->{"name::en"} =  'Hasan'; 
	    $product->{"name::fa"} =  'حسن'; 

	    $product->save();
	    
	    $product->load('translations');
 
	    $this->assertEquals($product->{"name::en"}, 'Hasan');
	    $this->assertEquals($product->{"name::fa"}, 'حسن');
	} 
}