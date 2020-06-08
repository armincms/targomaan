<?php 

namespace Armincms\Targomaan\Tests; 

use Armincms\Targomaan\Tests\Fixtures\Page;

class AssocTest extends AggregateTest
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp(): void
	{
	    parent::setUp(); 

	    $page = factory(Page::class)->create();
 
	    $page->{"name::fa"} =  'حسن'; 

	    $page->save();
	    
	    $page->load('translations');
 
	    $this->assertEquals($page->{"name::fa"}, 'حسن');
	} 
}